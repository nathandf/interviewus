<?php

namespace Model\Services;

use Contracts\SMSMessagerInterface;

class InterviewDispatcher
{
	private $interviewRepo;
	private $interviewQuestionRepo;
	private $intervieweeRepo;
	private $intervieweeAnswerRepo;
	private $interview;
	private $interviewee;
	private $organizationRepo;
	private $twilioPhoneNumberRepo;
	private $phoneRepo;
	private $smsMessager;

	public function __construct(
		InterviewRepository $interviewRepo,
		InterviewQuestionRepository $interviewQuestionRepo,
		IntervieweeAnswerRepository $intervieweeAnswerRepo,
		IntervieweeRepository $intervieweeRepo,
		OrganizationRepository $organizationRepo,
		TwilioPhoneNumberRepository $twilioPhoneNumberRepo,
		PhoneRepository $phoneRepo,
		SMSMessagerInterface $smsMessager
	) {
		$this->interviewRepo = $interviewRepo;
		$this->interviewQuestionRepo = $interviewQuestionRepo;
		$this->intervieweeAnswerRepo = $intervieweeAnswerRepo;
		$this->intervieweeRepo = $intervieweeRepo;
		$this->organizationRepo = $organizationRepo;
		$this->twilioPhoneNumberRepo = $twilioPhoneNumberRepo;
		$this->phoneRepo = $phoneRepo;
		$this->smsMessager = $smsMessager;
	}

	// Sends out interview questions to interviewees using the method specified
	// in the interview data.
	public function dispatch( $interview_id )
	{
		// Retrieve and set interview
		$interview = $this->interviewRepo->get( [ "*" ], [ "id" => $interview_id ], "single" );

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
	}

	private function dispatchSMSInterview()
	{
		// Get Interviewee
		$interviewee = $this->getInterviewee();

		// Get organization
		$organization = $this->organizationRepo->get(
			[ "*" ],
			[ "id" => $interviewee->organization_id ],
			"single"
		);

		// Get organizations twilio phone number
		$organization->twilioPhoneNumber = $this->twilioPhoneNumberRepo->get(
			[ "*" ],
			[ "id" => $organization->twilio_phone_number_id ],
			"single"
		);

		// Get questions data related to this ineterview
		$this->interview->questions = $this->interviewQuestionRepo->getAllByInterviewID( $this->interview->id );

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
					$this->smsMessager->setSenderE164PhoneNumber(
							$organization->twilioPhoneNumber->phone_number
						)
						->setRecipientCountryCode( $interviewee->phone->country_code )
						->setRecipientNationalNumber( $interviewee->phone->national_number )
						->setSMSBody( $question->body )
						->send();

					// Update the dispatch status of this question
					$this->interviewQuestionRepo->update(
						[ "dispatched" => 1 ],
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
				$organization->twilioPhoneNumber->phone_number
			)
			->setRecipientCountryCode( $interviewee->phone->country_code )
			->setRecipientNationalNumber( $interviewee->phone->national_number )
			->setSMSBody( "Interview complete! Thanks for your time. Your answers are being reviewed." )
			->send();

		// ...and update the interview's status to dispatched
		$this->interviewRepo->update(
			[ "status" => "complete" ],
			[ "id" => $this->interview->id ]
		);

		return;
	}

	private function dispatchWebInterview()
	{
		// Get questions data related to this ineterview
		$this->interview->questions = $this->interviewQuestionRepo->getAllByInterviewID( $this->interview->id );

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
