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
        $interviewTemplates = $interviewTemplateRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

        $positions = $positionRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

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
                    ->setDeploymentTypeID( $input->get( "deployment_type_id" ) )
                    ->setAccount( $this->account )
                    ->setPositionID( $position->id )
                    ->setOrganizationID( $this->organization->id )
                    ->build();

                if ( !is_null( $interview ) ) {
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

                            // Dispatch the first interview question immediately if interview
                            // status is active
                            // Dispatch the first interview question immediately if interview
                            // status is active
                            if ( $interview->status == "active" ) {
                                $interviewDispatcher->dispatch(
                                    $interviewRepo->get( [ "*" ], [ "id" => $interview->id ], "single" )
                                );
                            }

                            $this->session->addFlashMessage( "Interview successfully deployed" );
                            $this->session->setFlashMessages();

                            $this->view->redirect( "profile/" );

                        } catch ( \Exception $e ) {
                            $this->logger->error( $e );
                            $this->view->addApplicationError( $e->getMessage() );

                            // Update the interview with a conversation id so it can
                            // be dispatched to the right phone number
                            $interviewRepo->update(
                                [ "status" => "error" ],
                                [ "id" => $interview->id ]
                            );
                        }
                    }
                }
            }

            $inputValidator->addError( "deploy_interview", "You have reached your {$deploymentType->name} interview deployment limit. Upgrade your account for more interviews." );
        }

        $this->view->assign( "interviewee", $interviewee );
        $this->view->assign( "interviewTemplates", $interviewTemplates );
        $this->view->assign( "positions", $positions );
        $this->view->setErrorMessages( $inputValidator->getErrors() );
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );
        $this->view->assign( "flash_messages", $this->session->getFlashMessages() );

        $this->view->setTemplate( "profile/interviewee/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
