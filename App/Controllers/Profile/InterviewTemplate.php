<?php

namespace Controllers\Profile;

use \Core\Controller;

class InterviewTemplate extends Controller
{
    public function before()
    {
        $organizationRepo = $this->load( "organization-repository" );
        $interviewTemplateRepo = $this->load( "interview-template-repository" );

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => 0 ], "single" );

        // Ensure the current interview template is owned by this organization
        if (
            isset( $this->params[ "id" ] ) &&
            !in_array(
                $this->params[ "id" ],
                $interviewTemplateRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
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
        $interviewTemplateRepo = $this->load( "interview-template-repository" );
        $questionRepo = $this->load( "question-repository" );

        $interviewTemplate = $interviewTemplateRepo->get( [ "*" ], [ "id" => $this->params[ "id" ] ], "single" );
        $interviewTemplate->questions = $questionRepo->getAllByInterviewTemplateID( $interviewTemplate->id );

        if ( $input->exists() && $input->issetField( "update_existing_questions" ) ) {
            $existing_questions = $input->get( "existing_question" );
            if ( is_array( $existing_questions ) ) {
                $iteration = 1;
                foreach ( $existing_questions as $id => $body ) {
                    // Ensure question body isn't empty
                    if ( !is_null( $body ) && $body != "" ) {
                        $questionRepo->update(
                            [ "body" => $body, "placement" => $iteration ],
                            [ "id" => $id, "interview_template_id" => $this->params[ "id" ] ]
                        );
                    }
                    $iteration++;
                }
                $this->session->addFlashMessage( "Questions updated" );
                $this->session->setFlashMessages();
                $this->view->redirect( "profile/interview-template/" . $this->params[ "id" ] . "/" );
            }
        }

        if (
            $input->exists() &&
            $input->issetField( "new_question" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "body" => [
                        "required" => true
                    ]
                ],
                "new_question"
            )
        ) {
            $question = $questionRepo->insert([
                "interview_template_id" => $this->params[ "id" ],
                "question_type_id" => 1,
                "placement" => count( $interviewTemplate->questions ) + 1,
                "body" => $input->get( "body" )
            ]);
            $this->session->addFlashMessage( "Question added" );
            $this->session->setFlashMessages();
            $this->view->redirect( "profile/interview-template/" . $this->params[ "id" ] . "/" );
        }

        $this->view->assign( "interviewTemplate", $interviewTemplate );
        $this->view->assign( "error_messages", $inputValidator->getErrors() );
        $this->view->assign( "flash_messages", $this->session->getFlashMessages() );
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );

        $this->view->setTemplate( "profile/interview-template/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}