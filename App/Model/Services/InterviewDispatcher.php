<?php

namespace Model\Services;

use Contracts\SMSMessagerInterface;
use Model\Entities\Interview;

class InterviewDispatcher
{
	private $interviewRepo;
	private $interviewQuestionRepo;
	private $intervieweeRepo;
	private $intervieweeAnswerRepo;
	private $interview;
	private $interviewee;
	private $conversationRepo;
	private $twilioPhoneNumberRepo;
	private $phoneRepo;
	private $smsMessager;

	public function __construct(
		InterviewRepository $interviewRepo,
		InterviewQuestionRepository $interviewQuestionRepo,
		IntervieweeAnswerRepository $intervieweeAnswerRepo,
		IntervieweeRepository $intervieweeRepo,
		ConversationRepository $conversationRepo,
		TwilioPhoneNumberRepository $twilioPhoneNumberRepo,
		PhoneRepository $phoneRepo,
		SMSMessagerInterface $smsMessager
	) {
		$this->interviewRepo = $interviewRepo;
		$this->interviewQuestionRepo = $interviewQuestionRepo;
		$this->intervieweeAnswerRepo = $intervieweeAnswerRepo;
		$this->intervieweeRepo = $intervieweeRepo;
		$this->conversationRepo = $conversationRepo;
		$this->twilioPhoneNumberRepo = $twilioPhoneNumberRepo;
		$this->phoneRepo = $phoneRepo;
		$this->smsMessager = $smsMessager;
	}

	// Sends out interview questions to interviewees using the method specified
	// in the interview data.
	public function dispatch( Interview $interview )
	{
		$this->setInterview( $interview );

		// Retrieve and set interviewee and interviewee phone
		$interviewee = $this->intervieweeRepo->get(
			[ "*" ],
			[ "id" => $interview->interviewee_id ],
			"single"
		);

		$interviewee->phone = $this->phoneRepo->get(
			[ "*" ],
			[ "id" => $interviewee->phone_id ],
			"single"
		);

		$this->setInterviewee( $interviewee );

		switch ( $interview->deployment_type_id ) {
			case 1:
				$this->dispatchSMSInterview();
				break;
			case 2:
				$this->dispatchWebInterview();
				break;
		}

		// Return updated interview
		return $this->interviewRepo->get( [ "*" ], [ "id" => $interview->id ], "single" );
	}

	private function dispatchSMSInterview()
	{
		// Get Interviewee
		$interviewee = $this->getInterviewee();

		// Get the conversation for this interview
		$conversation = $this->conversationRepo->get( [ "*" ], [ "id" => $this->interview->conversation_id ], "single" );

		// Get twilio phone number from conversation
		$twilioPhoneNumber = $this->twilioPhoneNumberRepo->get(
			[ "*" ],
			[ "id" => $conversation->twilio_phone_number_id ],
			"single"
		);

		// Get questions data related to this ineterview
		$this->interview->questions = $this->interviewQuestionRepo->getAllByInterview( $this->interview );

		// Retrieve the answer for each question from the database if one exists
		foreach ( $this->interview->questions as $question ) {
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
					$message = $this->smsMessager->setSenderE164PhoneNumber(
							$twilioPhoneNumber->phone_number
						)
						->setRecipientCountryCode( $interviewee->phone->country_code )
						->setRecipientNationalNumber( $interviewee->phone->national_number )
						->setSMSBody( $question->body )
						->send();

					// Update the dispatch status of this question
					$this->interviewQuestionRepo->update(
						[
							"dispatched" => 1,
							"sms_sid" => $message->sid
						],
						[ "id" => $question->id ]
					);

					// Set the interview status to active if not already
					$this->interviewRepo->update(
						[ "status" => "active" ],
						[ "id" => $this->interview->id ]
					);
				}

				return;
			}
		}

		// if all questions have been dispatched, send interview complete message...
		$this->smsMessager->setSenderE164PhoneNumber(
				$twilioPhoneNumber->phone_number
			)
			->setRecipientCountryCode( $interviewee->phone->country_code )
			->setRecipientNationalNumber( $interviewee->phone->national_number )
			->setSMSBody( "Interview complete! Thanks for your time. Your answers are being reviewed." )
			->send();

		// ...and update the interview's status to complete
		$this->interviewRepo->update(
			[
				"status" => "complete",
				"conversation_id" => null
			],
			[ "id" => $this->interview->id ]
		);

		// Delete the conversation for this interview to free up the interviewee's
		// phone nubmer for another interview
		$this->conversationRepo->delete(
			[ "id" ],
			[ $this->interview->conversation_id ]
		);

		return;
	}

	private function dispatchWebInterview()
	{
		// Get questions data related to this ineterview
		$this->interview->questions = $this->interviewQuestionRepo->getAllByInterview( $this->interview );

		// Retrieve the answer for each question from the database if one exists
		foreach ( $this->interview->questions as $question ) {
			$question->answer = $this->intervieweeAnswerRepo->get(
				[ "*" ],
				[ "interview_question_id" => $question->id ],
				"single"
			);

			// If a question hasn't been answered, stop the process
			if ( is_null( $question->answer ) ) {

				return;
			}
		}

		// if all questions have been dispatched, mark interview as complete
		$this->interviewRepo->update(
			[ "status" => "complete" ],
			[ "id" => $this->interview->id ]
		);

		return;
	}

	public function answerNextQuestion( Interview $interview, $answer, $dispatch = true )
	{
		// Interview questions will be orderd in placement in ascending order
		$interview->questions = $this->interviewQuestionRepo->getAllByInterview(
			$interview
		);

		// Retrieve the interviewee's anwers to the interview questions.
		foreach ( $interview->questions as $question ) {
			$question->answer = $this->intervieweeAnswerRepo->get(
				[ "*" ],
				[ "interview_question_id" => $question->id ],
				"single"
			);

			// The first interview question for which the answer comes up null
			// is the next answerable question in the interview.
			if ( is_null( $question->answer ) ) {
				// Save the sms message body as the nterviewee answer
				$this->intervieweeAnswerRepo->insert([
					"interview_question_id" => $question->id,
					"body" => $answer
				]);
				// Once the interviewee's answer is saved. Break the loop and
				// dispatch the interview.
				break;
			}
		}

		// If there are more questions, they will be dispatched. If not, then
		// this interview's status will be updated to "complete"
		return $this->dispatch( $interview );
	}

	private function setInterview( \Model\Entities\Interview $interview )
	{
		$this->interview = $interview;
		return $this;
	}

	private function setInterviewee( \Model\Entities\Interviewee $interviewee )
	{
		$this->interviewee = $interviewee;
		return $this;
	}

	private function getInterviewee()
	{
		return $this->interviewee;
	}
}
