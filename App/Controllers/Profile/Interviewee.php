<?php

namespace Controllers\Profile;

use \Core\Controller;

class Interviewee extends Controller
{
    private $accountRepo;
    private $account;
    private $user;
    private $organization;

    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $countryRepo = $this->load( "country-repository" );
        $this->accountRepo = $this->load( "account-repository" );
        $accountUserRepo = $this->load( "account-user-repository" );
        $organizationRepo = $this->load( "organization-repository" );
        $intervieweeRepo = $this->load( "interviewee-repository" );
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

        // Ensure the current interviewee is owned by this organization
        if (
            isset( $this->params[ "id" ] ) &&
            !in_array(
                $this->params[ "id" ],
                $intervieweeRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
            )
        ) {
            $this->view->redirect( "profile/" );
        }
    }

    public function indexAction()
    {
        if ( !isset( $this->params[ "id" ] ) ) {
            $this->view->redirect( "profile/interview/new" );
        }

        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $interviewTemplateRepo = $this->load( "interview-template-repository" );
        $questionRepo = $this->load( "question-repository" );
        $interviewQuestionRepo = $this->load( "interview-question-repository" );
        $positionRepo = $this->load( "position-repository" );
        $interviewRepo = $this->load( "interview-repository" );
        $deploymentTypeRepo = $this->load( "deployment-type-repository" );
        $interviewDispatcher = $this->load( "interview-dispatcher" );
        $conversationProvisioner = $this->load( "conversation-provisioner" );
        $phoneRepo = $this->load( "phone-repository" );

        $interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $this->params[ "id" ] ], "single" );
        $interviewee->phone = $phoneRepo->get( [ "*" ], [ "id" => $interviewee->phone_id ], "single" );
        $interviewTemplates = $interviewTemplateRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

        $positions = $positionRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

        if (
            $input->exists() &&
            $input->issetField( "update_interviewee" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "first_name" => [
                        "required" => true,
                        "max" => 128
                    ],
                    "last_name" => [
                        "max" => 128
                    ],
                    "email" => [
                        "required" => true,
                        "email" => true
                    ],
                    "country_code" => [
                        "required" => true,
                        "number" => true
                    ],
                    "national_number" => [
                        "required" => true
                    ]
                ],
                "update_interviewee"
            )
        ) {
            $intervieweeRepo->update(
                [
                    "first_name" => $input->get( "first_name" ),
                    "last_name" => $input->get( "last_name" ),
                    "email" => $input->get( "email" )
                ],
                [ "id" => $this->params[ "id" ] ]
            );

            $phoneRepo->update(
                [
                    "country_code" => $input->get( "country_code" ),
                    "national_number" => $input->get( "national_number" )
                ],
                [ "id" => $interviewee->phone_id ]
            );

            $this->session->addFlashMessage( "Interviewee Updated" );
            $this->session->setFlashMessages();
            $this->view->redirect( "profile/interviewee/{$this->params[ "id" ]}/" );
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

                    // Provision a new converation for this interview if sms deployment
                    if ( $interview->deployment_type_id == 1 ) {

                        // Get the interviewee from the inteview
                        $interviewee = $interviewBuilder->getInterviewee();

                        // Get the interviewee's phone
                        $interviewee->phone = $phoneRepo->get( [ "*" ], [ "id" => $interviewee->phone_id ], "single" );

                        // Try to create a conversation for an sms interview deployement
                        try {
                            // Create a new conversation between a twilio number and
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
                        // An exception will be thrown if conversation limit between
                        // the inteviewee's phone number and the twilio phone number
                        // has been reached or if the this interviewee's phone number
                        // currently has a conversation with every twilio phone number.
                        // The later is unlikely but still possible.
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

        $this->view->assign( "interviewee", $interviewee );
        $this->view->assign( "interviewTemplates", $interviewTemplates );
        $this->view->assign( "positions", $positions );
        $this->view->setErrorMessages( $inputValidator->getErrors() );
        $this->view->assign( "flash_messages", $this->session->getFlashMessages() );

        $this->view->setTemplate( "profile/interviewee/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
