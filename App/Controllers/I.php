<?php

namespace Controllers;

use \Core\Controller;

class I extends Controller
{
    public function before()
    {
        $this->requireParam( "token" );
    }

    public function indexAction()
    {
        $interviewRepo = $this->load( "interview-repository" );
        $interviewQuestionRepo = $this->load( "interview-question-repository" );
        $intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
        $organizationRepo = $this->load( "organization-repository" );
        $interviewDispatcher = $this->load( "interview-dispatcher" );
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );

        $interview = $interviewRepo->get( [ "*" ], [ "token" => $this->params[ "token" ] ], "single" );
        if ( is_null( $interview ) ) {
            $this->view->setTemplate( "i/invalid-interview.tpl" );
            $this->view->render( "App/Views/Home.php" );

            return;
        }

        $interview->questions = $interviewQuestionRepo->getAllByInterviewID( $interview->id );

        foreach ( $interview->questions as $question ) {
            $question->answer = $intervieweeAnswerRepo->get(
                [ "*" ],
                [ "interview_question_id" => $question->id ],
                "single"
            );
        }

        $organization = $organizationRepo->get( [ "*" ], [ "id" => $interview->organization_id ], "single" );

        if (
            $input->exists() &&
            $input->issetField( "web_interview" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "equals-hidden" => $this->session->getSession( "csrf-token" ),
                        "required" => true
                    ],
                    "interviewee_answers" => [
                        "required" => true,
                        "is_array" => true
                    ]
                ],
                "web_interview"
            )
        ) {
            $interview_question_ids = $interviewQuestionRepo->get(
                [ "id" ],
                [ "interview_id" => $interview->id ],
                "raw"
            );

            $answers = $input->get( "interviewee_answers" );

            foreach ( $answers as $interview_question_id => $interviewee_answer ) {
                // Ensure the question the user is answering is owned by this organization
                if ( in_array( $interview_question_id, $interview_question_ids ) ) {
                    // Get the existing interviewee answer if one exists
                    $existing_interviewee_answer = $intervieweeAnswerRepo->get(
                        [ "*" ],
                        [ "interview_question_id" => $interview_question_id ],
                        "single"
                    );
                    // If one exists, update it by interview question id and
                    // continue on to the next quesiton
                    if ( !is_null( $existing_interviewee_answer ) ) {
                        $intervieweeAnswerRepo->update(
                            [ "body", $interviewee_answer ],
                            [ "interview_questions_id" => $interview_questions_id ]
                        );

                        continue;
                    }

                    $intervieweeAnswerRepo->insert([
                        "interview_question_id" => $interview_question_id,
                        "body" => $interviewee_answer
                    ]);
                }
            }

            $this->view->redirect( "i/" . $this->params[ "token" ] );
        }

        $this->view->assign( "interview", $interview );
        $this->view->assign( "organization", $organization );
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );
        $this->view->assign( "error_messages", $inputValidator->getErrors() );

        $this->view->setTemplate( "i/index.tpl" );
        $this->view->render( "App/Views/Home.php" );

        return;
    }
}
