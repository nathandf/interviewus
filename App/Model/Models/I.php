<?php

namespace Model\Models;

use Core\Model;

class I extends Model
{
	public function index()
	{
		$interviewRepo = $this->load( "interview-repository" );
        $this->interview= $interviewRepo->get( [ "*" ], [ "token" => $this->request->params( "token" ) ], "single" );

		// Load the organization that owns this interview
		$organizationRepo = $this->load( "organization-repository" );
		$this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->interview->organization_id ], "single" );

        // Load interview questions
        $interviewQuestionRepo = $this->load( "interview-question-repository" );
        $this->interview->questions = $interviewQuestionRepo->getAllByInterview( $this->interview);

        // Load the answers to the interview questions
		$intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
        foreach ( $this->interview->questions as $question ) {
            $question->answer = $intervieweeAnswerRepo->get(
                [ "*" ],
                [ "interview_question_id" => $question->id ],
                "single"
            );
        }
	}

	public function start()
	{
		$interviewRepo = $this->load( "interview-repository" );
        $this->interview = $interviewRepo->get( [ "*" ], [ "token" => $this->request->params( "token" ) ], "single" );

		// Dispatch this pending sms or web interview
		if ( $this->interview->status == "pending" ) {
			$interviewDispatcher = $this->load( "interview-dispatcher" );
			$interviewDispatcher->dispatch( $this->interview );

			// Update the start time
			$interviewRepo->update(
				[ "start_time" => date( "M j, Y \@ g:i:s a" ) ],
				[ "id" => $this->interview->id ]
			);
		}
	}

	public function webInterview()
	{
		$interviewRepo = $this->load( "interview-repository" );
        $this->interview = $interviewRepo->get( [ "*" ], [ "token" => $this->request->params( "token" ) ], "single" );

		// Stop if interview is invalid
        if ( is_null( $this->interview ) ) {
            return;
        }

		// Load the organization that owns this interview
		$organizationRepo = $this->load( "organization-repository" );
		$organization = $organizationRepo->get( [ "*" ], [ "id" => $this->interview->organization_id ], "single" );

		$interviewQuestionRepo = $this->load( "interview-question-repository" );
		$interview_question_ids = $interviewQuestionRepo->get(
			[ "id" ],
			[ "interview_id" => $this->interview->id ],
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
				$intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
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

		$interviewDispatcher = $this->load( "interview-dispatcher" );
		$this->interview = $interviewDispatcher->dispatch( $this->interview );

		// If the interview is complete, send the dispatching user a
		// a completion email
		if ( $this->interview->status == "complete" ) {
			$mailer = $this->load( "mailer" );
			$emailBuilder = $this->load( "email-builder" );
			$domainObjectFactory = $this->load( "domain-object-factory" );

			// Get the interviewee from the interview
			$intervieweeRepo = $this->load( "interviewee-repository" );
			$interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $this->interview->interviewee_id ], "single" );

			// Get the user that dispatched the interiew
			$userRepo = $this->load( "user-repository" );
			$user = $userRepo->get( [ "*" ], [ "id" => $this->interview->user_id ], "single" );

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
	}

	public function deploymentSuccessful()
	{
		$interviewRepo = $this->load( "interview-repository" );
		$this->interview = $interviewRepo->get( [ "*" ], [ "token" => $this->request->params( "token" ) ], "single" );
	}
}
