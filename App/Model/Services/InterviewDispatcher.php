<?php

namespace Model\Services;

class InterviewDispatcher
{
	private $interviewRepo;
	private $interviewQuestionRepo;
	private $intervieweeRepo;
	private $intervieweeAnswerRepo;

	public function __construct(
		InterviewRepository $interviewRepo,
		InterviewQuestionRepository $interviewQuestionRepo,
		IntervieweeAnswerRepository $intervieweeAnswerRepo,
		IntervieweeRepository $intervieweeRepo
	) {
		$this->interviewRepo = $interviewRepo;
		$this->interviewQuestionRepo = $interviewQuestionRepo;
		$this->intervieweeAnswerRepo = $intervieweeAnswerRepo;
		$this->intervieweeRepo = $intervieweeRepo;
	}

	// Sends out interview questions to interviewees using the method specified
	// in the interview data.
	public function dispatch( $interview_id )
	{
		// Retrieve interview data
		$interview = $this->interviewRepo->get( [ "*" ], [ "id" => $interview_id ], "single" );

		// Get questions data related to this ineterview
		$interview->questions = $this->interviewQuestionRepo->getAllByInterviewID( $interview->id );

		// Retrieve the answer for each question from the database if one exists
		foreach ( $interview->questions as $question ) {
			$question->answer = $this->intervieweeAnswerRepo->get(
				[ "*" ],
				[ "interview_question_id" => $question->id ],
				"single"
			);

			// If there is no answer for this question...
			if ( is_null( $question->answer ) ) {

				// ... and the current question in the loop has not been
				// dispatched(sent), then send the question via the method specified
				// in the interview data
				if ( $question->dispatched == false ) {
					// TODO Send the question

					// Update the dispatch status of this question
					$this->interviewQuestionRepo->update(
						[ "dispatched" => 1 ],
						[ "id" => $question->id ]
					);

					// Set the interview status to active if not already
					$this->interviewRepo->update(
						[ "status" => "active" ],
						[ "id" => $interview->id ]
					);
				}

				return;
			}
		}

		// if all questions have been dispatched, send interview complete message...
		// TODO Send "interview complete" message

		// ...and update the interview's status to dispatched
		$this->interviewRepo->update(
			[ "status" => "complete" ],
			[ "id" => $interview->id ]
		);

		return;
	}
}
