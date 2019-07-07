<?php

namespace Controllers\Profile;

use \Core\Controller;

class Position extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $countryRepo = $this->load( "country-repository" );
        $accountRepo = $this->load( "account-repository" );
        $accountUserRepo = $this->load( "account-user-repository" );
        $organizationRepo = $this->load( "organization-repository" );
        $positionRepo = $this->load( "position-repository" );

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

        // Ensure the current position is owned by this organization
        if (
            isset( $this->params[ "id" ] ) &&
            !in_array(
                $this->params[ "id" ],
                $positionRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
            )
        ) {
            $this->view->redirect( "profile/" );
        }
    }

    public function indexAction()
    {
        if ( !isset( $this->params[ "id" ] ) ) {
            $this->view->redirect( "profile/" );
        }

        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $interviewRepo = $this->load( "interview-repository" );
        $interviewQuestionRepo = $this->load( "interview-question-repository" );
        $intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $phoneRepo = $this->load( "phone-repository" );
        $positionRepo = $this->load( "position-repository" );
        $deploymentTypeRepo = $this->load( "deployment-type-repository" );

        $position = $positionRepo->get( [ "*" ], [ "id" => $this->params[ "id" ] ], "single" );

        $interviews = array_reverse(
            $interviewRepo->get(
                [ "*" ],
                [
                    "position_id" => $position->id,
                    "organization_id" => $this->organization->id
                ]
            )
        );

        foreach ( $interviews as $interview ) {
            $interview->deploymentType = $deploymentTypeRepo->get( [ "*" ], [ "id" => $interview->deployment_type_id ], "single" );
            $interview->interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $interview->interviewee_id ], "single" );
            $interview->position = $position;
            $interview->questions = $interviewQuestionRepo->get( [ "*" ], [ "interview_id" => $interview->id ] );

            foreach ( $interview->questions as $question ) {
                $question->answer = $intervieweeAnswerRepo->get( [ "*" ], [ "interview_question_id" => $question->id ], "single" );
            }
        }

        if (
            $input->exists() &&
            $input->issetField( "update_position" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "name" => [
                        "required" => true,
                        "max" => 128
                    ],
                    "description" => [
                        "max" => 512
                    ]
                ],
                "update_position"
            )
        ) {
            $positionRepo->update(
                [
                    "name" => trim( $input->get( "name" ) ),
                    "description" => trim( $input->get( "description" ) )
                ],
                [ "id" => $this->params[ "id" ] ]
            );

            $this->session->addFlashMessage( "Position details updated" );
            $this->session->setFlashMessages();

            $this->view->redirect( "profile/position/{$this->params[ "id" ]}/" );
        }

        $this->view->assign( "interviews", $interviews );
        $this->view->assign( "position", $position );
        $this->view->assign( "flash_messages", $this->session->getFlashMessages() );

        $this->view->setTemplate( "profile/position/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
