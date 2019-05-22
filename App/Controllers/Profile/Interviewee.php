<?php

namespace Controllers\Profile;

use \Core\Controller;

class Interviewee extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $countryRepo = $this->load( "country-repository" );
        $accountRepo = $this->load( "account-repository" );
        $accountUserRepo = $this->load( "account-user-repository" );
        $organizationRepo = $this->load( "organization-repository" );
        $intervieweeRepo = $this->load( "interviewee-repository" );

        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            $this->view->redirect( "sign-in" );
        }

        $this->account = $accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );

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
        $interviewDispatcher = $this->load( "interview-dispatcher" );

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
            $interview_type = "sms";
            $interview_type_account_property = "sms_interviews";
            if ( $input->get( "deployment_type_id" ) == 2 ) {
                $interview_type = "web";
                $interview_type_account_property = "web_interviews";
            }

            // Ensure there are sufficient interview credits in the account
            if (
                $this->account->{$interview_type_account_property} > 0 ||
                $this->account->{$interview_type_account_property} == -1
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

                // Reduce the number of interviews in the account by 1
                $accountRepo = $this->load( "account-repository" );
                $accountRepo->update(
                [ $interview_type_account_property => ( $this->account->{$interview_type_account_property} - 1 ) ],
                [ "id" => $this->account->id ]
                );

                $this->session->addFlashMessage( "Interview deployed successfully" );
                $this->session->setFlashMessages();

                $this->view->redirect( "profile/" );
            }

            $inputValidator->addError( "deploy_interview", "You have reached your {$interview_type} interview deployment limit. Upgrade your account for more interviews." );
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
