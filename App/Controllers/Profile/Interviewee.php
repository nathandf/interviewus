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
        $interviewRepo = $this->load( "interview-repository" );
        $interviewQuestionRepo = $this->load( "interview-question-repository" );
        $intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
        $interviewTemplateRepo = $this->load( "interview-template-repository" );
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
            $this->view->redirect( "profile/interviewees/" );
        }

        $requestValidator = $this->load( "request-validator" );
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $interviewTemplateRepo = $this->load( "interview-template-repository" );
        $questionRepo = $this->load( "question-repository" );
        $interviewQuestionRepo = $this->load( "interview-question-repository" );
        $positionRepo = $this->load( "position-repository" );
        $interviewRepo = $this->load( "interview-repository" );
        $intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
        $deploymentTypeRepo = $this->load( "deployment-type-repository" );
        $interviewDispatcher = $this->load( "interview-dispatcher" );
        $conversationProvisioner = $this->load( "conversation-provisioner" );
        $phoneRepo = $this->load( "phone-repository" );

        $interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $this->params[ "id" ] ], "single" );
        $interviewee->phone = $phoneRepo->get( [ "*" ], [ "id" => $interviewee->phone_id ], "single" );

        // Retrieve all interviews for this interviewee
        $interviewee->interviews = array_reverse( $interviewRepo->get( [ "*" ], [ "interviewee_id" => $interviewee->id ] ) );

        // Get all questons for each interview
        foreach ( $interviewee->interviews as $interview ) {
            $interview->deploymentType = $deploymentTypeRepo->get( [ "*" ], [ "id" => $interview->deployment_type_id ], "single" );
            $interview->position = $positionRepo->get( [ "*" ], [ "id" => $interview->position_id ], "single" );
            $interview->questions = $interviewQuestionRepo->get( [ "*" ], [ "interview_id" => $interview->id ] );
            // Get all interview questions
            foreach ( $interview->questions as $question ) {
                $question->answer = $intervieweeAnswerRepo->get( [ "*" ], [ "interview_question_id" => $question->id ], "single" );
            }
        }

        // Get all interview templates
        $interviewTemplates = $interviewTemplateRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

        $positions = $positionRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "update_interviewee" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
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
                    "first_name" => $this->request->post( "first_name" ),
                    "last_name" => $this->request->post( "last_name" ),
                    "email" => $this->request->post( "email" )
                ],
                [ "id" => $this->params[ "id" ] ]
            );

            $phoneRepo->update(
                [
                    "country_code" => $this->request->post( "country_code" ),
                    "national_number" => $this->request->post( "national_number" )
                ],
                [ "id" => $interviewee->phone_id ]
            );

            $this->request->addFlashMessage( "success", "Interviewee Updated" );
            $this->request->setFlashMessages();
            $this->view->redirect( "profile/interviewee/{$this->params[ "id" ]}/" );
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
            return [ "Interview:deploy", "Interviewee:deployInterview", null, null ];
        }

        $this->view->assign( "interviewee", $interviewee );
        $this->view->assign( "interviewTemplates", $interviewTemplates );
        $this->view->assign( "positions", $positions );
        $this->view->setErrorMessages( $requestValidator->getErrors() );
        $this->view->assign( "flash_messages", $this->request->getFlashMessages() );

        $this->view->setTemplate( "profile/interviewee/index.tpl" );
        $this->view->render( "App/Views/Index.php" );
    }
}
