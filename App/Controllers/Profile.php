<?php

namespace Controllers;

use \Core\Controller;

class Profile extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $this->accountRepo = $this->load( "account-repository" );
        $accountUserRepo = $this->load( "account-user-repository" );
        $organizationRepo = $this->load( "organization-repository" );
        $this->logger = $this->load( "logger" );

        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            return [ null, "DefaultView:redirect", null, "sign-in" ];
        }

        $this->account = $this->accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );
        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );
    }

    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $interviewTemplateRepo = $this->load( "interview-template-repository" );
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $positionRepo = $this->load( "position-repository" );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "new_interviewee" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
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

            return [ "Interviewee:create", "Interviewee:create", null, null ];
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "new_interview_template" ) != "" &&
            $requestValidator->validate(
                $this->request,
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

            return [ "InterviewTemplate:create", "InterviewTemplate:create", null, null ];
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "deploy-interview" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "equals-hidden" => $this->request->session( "csrf-token" ),
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
            return [ "Interview:deploy", "Profile:deployInterview", null, null ];
        }

        return [ "Profile:index", "Profile:showAll", null, $requestValidator->getErrors() ];
    }

    public function archiveAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $interviewRepo = $this->load( "interview-repository" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "interview_id" => [
                        "required" => true,
                        "in_array" => $interviewRepo->get(
                            [ "id" ],
                            [
                                "id" => $this->request->post( "interview_id" ),
                                "organization_id" => $this->organization->id
                            ],
                            "raw"
                        )
                    ],
                ],
                "archive"
            )
        ) {
            $interviewRepo->update(
                [ "mode" => "archived" ],
                [ "id" => $this->request->post( "interview_id" ) ]
            );

            echo( "success" );
            die();
            exit();
        }

        echo( $requestValidator->getError()[ 0 ] );
        die();
        exit();
    }

    public function shareInterviewAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $interviewRepo = $this->load( "interview-repository" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "interview_id" => [
                        "required" => true,
                        "in_array" => $interviewRepo->get(
                            [ "id" ],
                            [
                                "id" => $this->request->post( "interview_id" ),
                                "organization_id" => $this->organization->id
                            ],
                            "raw"
                        )
                    ],
                    "recipients" => [
                        "required" => true
                    ]
                ],
                "share"
            )
        ) {
            $interviewRepo = $this->load( "interview-repository" );
            $interviewQuestionRepo = $this->load( "interview-question-repository" );
            $intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
            $intervieweeRepo = $this->load( "interviewee-repository" );
            $positionRepo = $this->load( "position-repository" );
            $domainObjectFactory = $this->load( "domain-object-factory" );
            $emailBuilder = $this->load( "email-builder" );
            $mailer = $this->load( "mailer" );
            $htmlInterviewResultsBuilder = $this->load( "html-interview-results-builder" );

            // Compile all interview questions and their answers into one large object
            $interview = $interviewRepo->get( [ "*" ], [ "id" => $this->request->post( "interview_id" ) ], "single" );

            $interview->interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $interview->interviewee_id ], "single" );
            $interview->position = $positionRepo->get( [ "*" ], [ "id" => $interview->position_id ], "single" );
            $interview->questions = $interviewQuestionRepo->get( [ "*" ], [ "interview_id" => $interview->id ] );

            foreach ( $interview->questions as $question ) {
                $question->answer = $intervieweeAnswerRepo->get( [ "*" ], [ "interview_question_id" => $question->id ], "single" );
            }

            // Build the inteview results into a nice html form to be used in the
            // email template
            $html_interview_results = $htmlInterviewResultsBuilder->build( $interview );

            // Parse interview recipients
            $recipients = explode( ",", strtolower( str_replace( ", ", ",", $this->request->post( "recipients" ) ) ) );

            if ( is_array( $recipients ) ) {
                $i = 0;
                foreach ( $recipients as $email ) {
                    // Only send an email to the first 5 recipients...
                    if ( $i < 5 ) {
                        // ... and the email provided is a valid email address
                        if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                            // Build the email context to be used by the email template
                            $emailContext = $domainObjectFactory->build( "EmailContext" );
                            $emailContext->addProps([
                                "user" =>  $this->user->getFullName(),
                                "interviewee" => $interview->interviewee->getFullName(),
                                "interview_results" => $html_interview_results
                            ]);

                            // Notify admin of user feedback
                            $resp = $mailer->setTo( $email, "Contact" )
                                ->setFrom( $this->user->email, $this->user->getFullName() )
                                ->setSubject( "Interview Results | {$interview->interviewee->getFullName()} | {$interview->position->name}" )
                                ->setContent( $emailBuilder->build( "interview-results.html", $emailContext ) )
                                ->mail();
                        }
                    }
                    $i++;
                }
            }

            echo( "success" );
            die();
            exit();
        }

        if ( isset( $requestValidator->getErrors()[ "share" ] ) == true ) {
            echo( $requestValidator->getErrors()[ "share" ][ 0 ] );
            die();
            exit();
        }

        echo( "failure" );
        die();
        exit();
    }

    public function logout()
    {
        return [ "Profile:logout", "Home:redirect", null, "" ];
    }
}
