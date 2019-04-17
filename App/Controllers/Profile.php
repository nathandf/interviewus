<?php

namespace Controllers;

use \Core\Controller;

class Profile extends Controller
{
    public function before()
    {
        $countryRepo = $this->load( "country-repository" );
        $organizationRepo = $this->load( "organization-repository" );

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => 0 ], "single" );

        $this->view->assign( "countries", $countryRepo->get( [ "*" ] ) );
    }

    public function indexAction()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $interviewRepo = $this->load( "interview-repository" );
        $interviewQuestionRepo = $this->load( "interview-question-repository" );
        $intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $interviewTemplateRepo = $this->load( "interview-template-repository" );
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $phoneRepo = $this->load( "phone-repository" );
        $positionRepo = $this->load( "position-repository" );
        $questionRepo = $this->load( "question-repository" );
        $interviewDispatcher = $this->load( "interview-dispatcher" );

        $interviews = $interviewRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

        foreach ( $interviews as $interview ) {
            $interview->interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $interview->interviewee_id ], "single" );
            $interview->position = $positionRepo->get( [ "*" ], [ "id" => $interview->position_id ], "single" );
            $interview->questions = $interviewQuestionRepo->get( [ "*" ], [ "interview_id" => $interview->id ] );

            foreach ( $interview->questions as $question ) {
                $question->answer = $intervieweeAnswerRepo->get( [ "*" ], [ "interview_question_id" => $question->id ], "single" );
            }
        }

        $interviewTemplates = $interviewTemplateRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

        $positions = $positionRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

        $interviewees = $intervieweeRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

        if (
            $input->exists() &&
            $input->issetField( "new_interviewee" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "name" => [
                        "required" => true
                    ],
                    "email" => [
                        "required" => true,
                        "email" => true
                    ],
                    "country_code" => [
                        "required" => true
                    ],
                    "national_number" => [
                        "required" => true,
                        "phone" => true
                    ]
                ],
                "new_interviewee"
                )
        ) {
            $phone = $phoneRepo->insert([
                "country_code" => $input->get( "country_code" ),
                "national_number" => $input->get( "national_number" )
            ]);

            $interviewee = $intervieweeRepo->insert([
                "organization_id" => $this->organization->id,
                "first_name" => $input->get( "name" ),
                "email" => $input->get( "email" ),
                "phone_id" => $phone->id
            ]);

            // Update the first and last name
            $interviewee->setNames( $interviewee->first_name );

            if (
                !is_null( $interviewee->getFirstName() ) &&
                !is_null( $interviewee->getLastName() )
            ) {
                $intervieweeRepo->update(
                    [
                        "first_name" => $interviewee->getFirstName(),
                        "last_name" => $interviewee->getLastName()
                    ],
                    [
                        "id" => $interviewee->id
                    ]
                );
            }

            $this->view->redirect( "profile/interviewee/" . $interviewee->id . "/" );
        }

        if (
            $input->exists() &&
            $input->issetField( "new_interview_template" ) &&
            $inputValidator->validate(
                $input,
                [
                    "name" => [],
                    "description" => [],
                    "questions" => [
                        "required" => true,
                        "is_array" => true
                    ]
                ],
                "new_interview_template"
            )
        ) {
            $interviewTemplate = $interviewTemplateRepo->insert([
                "name" => $input->get( "name" ),
                "description" => $input->get( "description" ),
                "organization_id" => $this->organization->id
            ]);

            $questions = $input->get( "questions" );

            $i = 1;
            foreach ( $questions as $question ) {
                $questionRepo->insert([
                    "interview_template_id" => $interviewTemplate->id,
                    "question_type_id" => 1,
                    "placement" => $i,
                    "body" => $question
                ]);
                $i++;
            }

            $this->view->redirect( "profile/interview-template/" . $interviewTemplate->id . "/" );
        }

        if (
            $input->exists() &&
            $input->issetField( "deploy-interview" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "equals-hidden" => $this->session->getSession( "csrf-token" ),
                        "required" => true
                    ],
                    "deployment_type_id" => [
                        "required" => true,
                        "in_array" => [ 1, 2 ]
                    ],
                    "interviewee_id" => [
                        "required" => true,
                        "in_array" => $intervieweeRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
                    ],
                    "position_id" => [
                        "in_array" => $positionRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
                    ],
                    "interview_template_id" => [
                        "required" => true,
                        "in_array" => $interviewTemplateRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
                    ],
                    "schedule_type" => [
                        "required" => true,
                        "in_array" => [ 1, 2 ]
                    ],
                    "date" => [],
                    "Hour" => [],
                    "Minute" => [],
                    "Meridian" => []
                ],
                "deploy_interview"
            )
        ) {
            $position_id = $input->get( "position_id" );

            if ( $input->get( "position" ) != "" ) {
                $position = $positionRepo->insert([
                    "organization_id" => $this->organization->id,
                    "name" => $input->get( "position" )
                ]);

                $position_id = $position->id;
            }

            $scheduled_time = null;
            $status = "active";

            if (
                $input->get( "schedule_type" ) == 2 &&
                $input->get( "date" ) != ""
            ) {
                $status = "scheduled";
                $scheduled_time = $input->get( "date" ) . " " . $input->get( "Hour" ) . ":" . $input->get( "Minute" ) . $input->get( "Meridian" );
            }

            $interview = $interviewRepo->insert([
                "deployment_type_id" => $input->get( "deployment_type_id" ),
                "organization_id" => $this->organization->id,
                "interviewee_id" => $input->get( "interviewee_id" ),
                "interview_template_id" => $input->get( "interview_template_id" ),
                "position_id" => $position_id,
                "status" => $status,
                "scheduled_time" => $scheduled_time,
                "token" => md5( microtime() ) . "-" . $this->organization->id . "-" . $input->get( "interviewee_id" )
            ]);

            // Create the questions for this interview from the interview template
            // questions
            $questions = $questionRepo->getAllByInterviewTemplateID(
                $interview->interview_template_id
            );

            foreach ( $questions as $question ) {
                $interviewQuestionRepo->insert([
                    "interview_id" => $interview->id,
                    "placement" => $question->placement,
                    "body" => $question->body
                ]);
            }

            // Dispatch the first interview question immediately if interview
            // status is active
            if ( $status == "active" ) {
                $interviewDispatcher->dispatch( $interview->id );
            }

            $this->view->redirect( "profile/" );
        }

        $this->view->assign( "interviews", $interviews );
        $this->view->assign( "interviewTemplates", $interviewTemplates );
        $this->view->assign( "interviewees", $interviewees );
        $this->view->assign( "positions", $positions );
        $this->view->setErrorMessages( $inputValidator->getErrors() );
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );

        $this->view->setTemplate( "profile/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
