<?php

namespace Controllers;

use \Core\Controller;

class Profile extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $countryRepo = $this->load( "country-repository" );
        $this->accountRepo = $this->load( "account-repository" );
        $accountUserRepo = $this->load( "account-user-repository" );
        $organizationRepo = $this->load( "organization-repository" );
        $this->logger = $this->load( "logger" );

        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            $this->view->redirect( "sign-in" );
        }

        $this->account = $this->accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );
        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );

        $this->view->assign( "countries", $countryRepo->get( [ "*" ] ) );
        $this->view->assign( "account", $this->account );
        $this->view->assign( "organization", $this->organization );
        $this->view->assign( "user", $this->user );
    }

    public function indexAction()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $interviewRepo = $this->load( "interview-repository" );
        $interviewQuestionRepo = $this->load( "interview-question-repository" );
        $intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
        $interviewTemplateRepo = $this->load( "interview-template-repository" );
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $phoneRepo = $this->load( "phone-repository" );
        $positionRepo = $this->load( "position-repository" );
        $questionRepo = $this->load( "question-repository" );
        $interviewBuilder = $this->load( "interview-builder" );
        $interviewDispatcher = $this->load( "interview-dispatcher" );
        $deploymentTypeRepo = $this->load( "deployment-type-repository" );
        $conversationProvisioner = $this->load( "conversation-provisioner" );

        $interviews = array_reverse(
            $interviewRepo->get(
                [ "*" ],
                [
                    "organization_id" => $this->organization->id,
                    "mode" => "visible"
                ]
            )
        );

        foreach ( $interviews as $interview ) {
            $interview->deploymentType = $deploymentTypeRepo->get( [ "*" ], [ "id" => $interview->deployment_type_id ], "single" );
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
                "national_number" => $input->get( "national_number" ),
                "e164_phone_number" => "+" . $input->get( "country_code" ) . $input->get( "national_number" )
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
            $position = $positionRepo->get( [ "*" ], [ "id" => $input->get( "position_id" ) ], "single" );
            // Create a new position if the one submitted does not exist
            if ( $input->get( "position" ) != "" ) {
                $position = $positionRepo->insert([
                    "organization_id" => $this->organization->id,
                    "name" => $input->get( "position" )
                ]);
            }

            // Ensure account has enough of the correct interview credits to create
            // this interview
            $deploymentType = $deploymentTypeRepo->get( [ "*" ], [ "id" => $input->get( "deployment_type_id" ) ], "single" );

            if ( $this->account->validateInterviewCredit( $deploymentType ) ) {
                // Build and dispatch the interview. Will return null if insufficient
                // interview credits in the account
                $interviewBuilder = $this->load( "interview-builder" );

                // Set scheduled time and status if deploying later. Default status
                // is "active"
                if (
                    $input->get( "schedule_type" ) == 2 &&
                    $input->get( "date" ) != ""
                ) {
                    $interviewBuilder->setStatus( "scheduled" )
                        ->setScheduledTime(
                            $input->get( "date" ) . " " . $input->get( "Hour" ) . ":" . $input->get( "Minute" ) . $input->get( "Meridian" )
                        );
                }

                $interview = $interviewBuilder->setIntervieweeID( $input->get( "interviewee_id" ) )
                    ->setInterviewTemplateID( $input->get( "interview_template_id" ) )
                    ->setDeploymentTypeID( $deploymentType->id )
                    ->setAccount( $this->account )
                    ->setPositionID( $position->id )
                    ->setUserID( $this->user->id )
                    ->setOrganizationID( $this->organization->id )
                    ->build();

                if ( !is_null( $interview ) ) {
                    // Interview deployment flag. Default true.
                    $interview_deployment_successful = true;

                    // Debit the account of the interview credits for the deployment
                    // type provided
                    $this->account = $this->accountRepo->debitInterviewCredits(
                        $this->account,
                        $deploymentType
                    );

                    // Provision a new conversation for this interview if sms deployment
                    if ( $interview->deployment_type_id == 1 ) {

                        // Get the interviewee from the interview
                        $interviewee = $interviewBuilder->getInterviewee();

                        // Get the interviewee's phone
                        $interviewee->phone = $phoneRepo->get( [ "*" ], [ "id" => $interviewee->phone_id ], "single" );

                        // Try to create a conversation for an sms interview deployement
                        try {
                            // Create a new conversation between a twilio numbe and
                            // the interviewee's phone number
                            $conversation = $conversationProvisioner->provision(
                                $interviewee->phone->e164_phone_number
                            );

                            // Update the interview with a conversation id so it can
                            // be dispatched to the right phone number
                            $interviewRepo->update(
                                [ "conversation_id" => $conversation->id ],
                                [ "id" => $interview->id ]
                            );
                        } catch ( \Exception $e ) {
                            // Log the error and pass the error message to the view
                            $this->logger->error( $e );
                            $this->view->addApplicationError( $e->getMessage() );

                            // Refund the account for the interview
                            $accountProvisioner = $this->load( "account-provisioner" );
                            $accountProvisioner->refundInterview( $this->account, $interview );

                            // Remove the interview from the records
                            $interviewRepo->delete(
                                [ "id" ],
                                [ $interview->id ]
                            );

                            $interview_deployment_successful = false;
                        }
                    }

                    if ( $interview_deployment_successful && $interview->status != "scheduled" ) {
                        // Send interviewee email prompting to start interview
                        $mailer = $this->load( "mailer" );
                        $emailBuilder = $this->load( "email-builder" );
                        $domainObjectFactory = $this->load( "domain-object-factory" );

                        $interviewee = $interviewBuilder->getInterviewee();

                        $emailContext = $domainObjectFactory->build( "EmailContext" );
                        $emailContext->addProps([
                            "full_name" => $interviewee->getFullName(),
                            "first_name" => $interviewee->getFirstName(),
                            "interview_token" => $interview->token,
                            "sent_by" => $this->user->getFullName()
                        ]);

                        $resp = $mailer->setTo( $interviewee->email, $interviewee->getFullName() )
                            ->setFrom( "noreply@interviewus.net", "InterviewUs" )
                            ->setSubject( "You have a pending interivew: {$interviewee->getFullName()}" )
                            ->setContent( $emailBuilder->build( "interview-dispatch-notification.html", $emailContext ) )
                            ->mail();

                            $this->session->addFlashMessage( ucfirst( $deploymentType->name ) . " interview successfully deployed" );
                            $this->session->setFlashMessages();

                            $this->view->redirect( "profile/" );
                    }
                }
            } else {
                $inputValidator->addError( "deploy_interview", "You have reached your {$deploymentType->name} interview deployment limit. Upgrade your account for more interviews." );
            }
        }

        $this->view->assign( "interviews", $interviews );
        $this->view->assign( "interviewTemplates", $interviewTemplates );
        $this->view->assign( "interviewees", $interviewees );
        $this->view->assign( "positions", $positions );
        $this->view->setErrorMessages( $inputValidator->getErrors() );
        $this->view->assign( "flash_messages", $this->session->getFlashMessages() );

        $this->view->setTemplate( "profile/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }

    public function archiveAction()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $interviewRepo = $this->load( "interview-repository" );

        if (
            $input->exists() &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "interview_id" => [
                        "required" => true,
                        "in_array" => $interviewRepo->get(
                            [ "id" ],
                            [
                                "id" => $input->get( "interview_id" ),
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
                [ "id" => $input->get( "interview_id" ) ]
            );

            echo( "success" );
            die();
            exit();
        }

        echo( $inputValidator->getError()[ 0 ] );
        die();
        exit();
    }

    public function shareInterviewAction()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $interviewRepo = $this->load( "interview-repository" );

        if (
            $input->exists() &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "interview_id" => [
                        "required" => true,
                        "in_array" => $interviewRepo->get(
                            [ "id" ],
                            [
                                "id" => $input->get( "interview_id" ),
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
            $interview = $interviewRepo->get( [ "*" ], [ "id" => $input->get( "interview_id" ) ], "single" );

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
            $recipients = explode( ",", strtolower( str_replace( ", ", ",", $input->get( "recipients" ) ) ) );

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

        if ( isset( $inputValidator->getErrors()[ "share" ] ) == true ) {
            echo( $inputValidator->getErrors()[ "share" ][ 0 ] );
            die();
            exit();
        }

        echo( "failure" );
        die();
        exit();
    }

    public function logout()
    {
        $userAuth = $this->load( "user-authenticator" );
        $userAuth->logout();
        $this->view->redirect( "" );
    }
}
