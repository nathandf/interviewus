<?php

namespace Controllers;

use \Core\Controller;

class I extends Controller
{
    public function before()
    {
        if ( $this->issetParam( "token" ) === false ) {
            return [ null, "Error:e404", null, null ];
        }
    }

    public function indexAction()
    {
        $interviewRepo = $this->load( "interview-repository" );
        $interviewQuestionRepo = $this->load( "interview-question-repository" );
        $intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
        $organizationRepo = $this->load( "organization-repository" );
        $interviewDispatcher = $this->load( "interview-dispatcher" );

        $requestValidator = $this->load( "request-validator" );

        $interview = $interviewRepo->get( [ "*" ], [ "token" => $this->params[ "token" ] ], "single" );

        // Redirect to error page if interview is invalid
        if ( is_null( $interview ) ) {
            $this->view->setTemplate( "i/invalid-interview.tpl" );
            $this->view->render( "App/Views/Index.php" );

            return;
        }

        // Load the organization that owns this interview
        $organization = $organizationRepo->get( [ "*" ], [ "id" => $interview->organization_id ], "single" );

        // Redirect to interview is complete, redirect to the interview complete page
        if ( $interview->status == "complete" ) {
            $this->view->redirect( "i/{$this->params[ "token" ]}/interview-complete" );
        }

        // Redirect to interview deployment success screen if the interview status
        // is active and interview is of the SMS deployment type
        if (
            $interview->deployment_type_id == 1 &&
            $interview->status == "active"
        ) {
            $this->view->redirect( "i/{$this->params[ "token" ]}/deployment-successful" );
        }

        // Load interview questions
        $interview->questions = $interviewQuestionRepo->getAllByInterview( $interview );

        // Load the answers to the interview questions
        foreach ( $interview->questions as $question ) {
            $question->answer = $intervieweeAnswerRepo->get(
                [ "*" ],
                [ "interview_question_id" => $question->id ],
                "single"
            );
        }

        // Dispatch the interview
        if (
            $this->request->is( "post" ) &&
            $this->request->post( "start_interview" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ]
                ],
                "start_interview"
            )
        ) {
            // Dispatch this pending sms or web interview
            if ( $interview->status == "pending" ) {
                $interviewDispatcher->dispatch( $interview );

                // If the interview is an sms interview, redirect the deployment
                // successful page
                if ( $interview->deployment_type_id == 1 ) {
                    $this->view->redirect( "i/{$this->params[ "token" ]}/deployment-successful" );
                }
            }
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "web_interview" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "equals-hidden" => $this->request->session( "csrf-token" ),
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

            $answers = $this->request->post( "interviewee_answers" );

            foreach ( $answers as $interview_question_id => $interviewee_answer ) {
                // Ensure the question the user is answering is owned by this organization
                if (
                    in_array( $interview_question_id, $interview_question_ids ) &&
                    $interviewee_answer != ""
                ) {
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
                            [ "body" => trim( $interviewee_answer ) ],
                            [ "interview_question_id" => $interview_question_id ]
                        );

                        continue;
                    }

                    $intervieweeAnswerRepo->insert([
                        "interview_question_id" => $interview_question_id,
                        "body" => $interviewee_answer
                    ]);

                    // Update the interview question to deployed
                    $interviewQuestionRepo->update(
                        [ "dispatched" => 1 ],
                        [ "id" => trim( $interview_question_id ) ]
                    );
                }
            }

            $interview = $interviewDispatcher->dispatch( $interview );

            // If the interview is complete, send the dispatching user a
            // a completion email
            if ( $interview->status == "complete" ) {
                $mailer = $this->load( "mailer" );
                $emailBuilder = $this->load( "email-builder" );
                $domainObjectFactory = $this->load( "domain-object-factory" );

                // Get the interviewee from the interview
                $intervieweeRepo = $this->load( "interviewee-repository" );
                $interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $interview->interviewee_id ], "single" );

                // Get the user that dispatched the interiew
                $userRepo = $this->load( "user-repository" );
                $user = $userRepo->get( [ "*" ], [ "id" => $interview->user_id ], "single" );

                $emailContext = $domainObjectFactory->build( "EmailContext" );
                $emailContext->addProps([
                    "interviewee_name" => $interviewee->getFullName()
                ]);

                $resp = $mailer->setTo( $user->email, $user->getFullName() )
                    ->setFrom( "noreply@interviewus.net", "InterviewUs" )
                    ->setSubject( $interviewee->getFirstName() . " has completed their interview" )
                    ->setContent( $emailBuilder->build( "interview-completion-notification.html", $emailContext ) )
                    ->mail();
            }

            $this->view->redirect( "i/" . $this->params[ "token" ] . "/" );
        }

        $this->view->assign( "interview", $interview );
        $this->view->assign( "organization", $organization );
        $this->view->assign( "error_messages", $requestValidator->getErrors() );

        $this->view->setTemplate( "i/index.tpl" );
        $this->view->render( "App/Views/Index.php" );

        return;
    }

    public function deploymentSuccessfulAction()
    {
        return [ null, "I:deploymentSuccessful", null, null ];
    }

    public function interviewCompleteAction()
    {
        return [ null, "I:interviewComplete", null, null ];
    }
}
