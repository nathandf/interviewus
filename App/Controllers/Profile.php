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
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $interviewTemplateRepo = $this->load( "interview-template-repository" );
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $phoneRepo = $this->load( "phone-repository" );
        $positionRepo = $this->load( "position-repository" );
        $questionRepo = $this->load( "question-repository" );

        $interviews = $interviewRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );
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
