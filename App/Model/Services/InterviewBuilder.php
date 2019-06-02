<?php

namespace Model\Services;

use Model\Entities\Account;

class InterviewBuilder
{
	private $accountRepo;
	private $interviewRepo;
	private $questionRepo;
	private $interviewQuestionRepo;
	private $intervieweeRepo;
	private $conversationCoordinator;
	private $deployment_type_id;
	private $interview_type;
	private $interview_type_account_property;
	private $interview;
	private $interview_template_id;
	private $position_id;
	private $conversation;
	private $account;
	private $organization_id;
	private $schedule_type; // 1 = immidiately; 2 = scheduled.
	private $scheduled_time = null;
	private $status = "active";
	private $statuses = [ "active", "scheduled" ];
	private $deployment_type_ids = [ 1, 2 ];

	public function __construct(
		AccountRepository $accountRepo,
		InterviewRepository $interviewRepo,
		QuestionRepository $questionRepo,
		InterviewQuestionRepository $interviewQuestionRepo,
		IntervieweeRepository $intervieweeRepo,
		ConversationCoordinator $conversationCoordinator
	) {
		$this->accountRepo = $accountRepo;
		$this->interviewRepo = $interviewRepo;
		$this->questionRepo = $questionRepo;
		$this->interviewQuestionRepo = $interviewQuestionRepo;
		$this->intervieweeRepo = $intervieweeRepo;
		$this->conversationCoordinator = $conversationCoordinator;
	}

	// Build interview
	public function build()
	{
		// Get the interviewee for this interview. Determine if interview
		// can be properly deployed
		$interviewee = $this->intervieweeRepo->get(
			[ "*" ],
			[ "id" => $this->getIntervieweeID() ],
			"single"
		);

		// Ensure interviewee is valid
		if ( !is_null( $interviewee ) ) {
			switch ( $this->getDeploymentTypeID() ) {
				case 1:
					$conversation = $this->conversationCoordinator->create( $interviewee->phone_id );
					$conversation_id = $conversation->id;
					break;

				case 2:
					$conversation_id = null;
					break;
			}

			if ( $this->validateAccountCredit() ) {
				$interview = $this->interviewRepo->insert([
					"deployment_type_id" => $this->getDeploymentTypeID(),
					"organization_id" => $this->getOrganizationID(),
					"conversation_id" => $conversation_id,
					"interviewee_id" => $interviewee->id,
					"interview_template_id" => $this->getInterviewTemplateID(),
					"position_id" => $this->getPositionID(),
					"status" => $this->getStatus(),
					"scheduled_time" => $this->getScheduledTime(),
					"token" => md5( microtime() ) . "-" . $this->getOrganizationID() . "-" . $interviewee->id
				]);

				// Create the questions for this interview from the interview template
				// questions
				$questions = $this->questionRepo->getAllByInterviewTemplateID(
					$interview->interview_template_id
				);

				foreach ( $questions as $question ) {
					$this->interviewQuestionRepo->insert([
						"interview_id" => $interview->id,
						"placement" => $question->placement,
						"body" => $question->body
					]);
				}

				// Reduce the number of interviews in the account by 1
                $this->accountRepo->update(
                    [ $this->getInterviewTypeAccountProperty() => ( $this->account->{$this->getInterviewTypeAccountProperty()} - 1 ) ],
                    [ "id" => $this->account->id ]
                );

				return $interview;
			}

			// Return null if insufficient interviews
			return null;
		}

		throw new \Exception( "Invalid interviewee" );
	}

	private function validateAccountCredit()
	{
		$account = $this->getAccount();
		// Ensure there are sufficient interview credits in the account. -1
		// means the account has unlimited interviews
		if (
			$account->{$this->getInterviewTypeAccountProperty()} > 0 ||
			$account->{$this->getInterviewTypeAccountProperty()} == -1
		) {
			return true;
		}

		return false;
	}

	public function setIntervieweeID( $interviewee_id )
	{
		$this->interviewee_id = $interviewee_id;
		return $this;
	}

	public function getIntervieweeID()
	{
		if ( isset( $this->interviewee_id ) ) {
			return $this->interviewee_id;
		}

		throw new \Exception( "Invalid interviewee" );
	}

	public function setDeploymentTypeID( $deployment_type_id )
	{
		if ( in_array( $deployment_type_id, $this->deployment_type_ids ) ) {
			$this->deployment_type_id = $deployment_type_id;
			// Set interview_type property and interivew_type_account_property
			switch ( $deployment_type_id ) {
				case 1:
					$this->setInterviewType( "sms" );
					$this->setInterviewTypeAccountProperty( "sms" );
					break;
				case 2:
					$this->setInterviewType( "web" );
					$this->setInterviewTypeAccountProperty( "sms" );
					break;
			}
			return $this;
		}

		throw new \Exception( "Invalid deployment_type_id" );
	}

	public function getDeploymentTypeID()
	{
		if ( isset( $this->deployment_type_id ) ) {
			return $this->deployment_type_id;
		}

		throw new \Exception( "Invalid deployment type id" );

	}

	public function setInterviewType( $interview_type )
	{
		$this->interview_type = $interview_type;
		return $this;
	}

	public function getInterviewType()
	{
		return $this->interview_type;
	}

	public function setInterviewTypeAccountProperty( $interview_type )
	{
		$this->interview_type_account_property = $interview_type . "_interviews";
		return $this;
	}

	private function getInterviewTypeAccountProperty()
	{
		return $this->interview_type_account_property;
	}

	public function setAccount( Account $account )
	{
		$this->account = $account;
		return $this;
	}

	public function getAccount()
	{
		if ( isset( $this->account ) ) {
			return $this->account;
		}

		throw new \Exception( "Account not set" );
	}

	public function setOrganizationID( $organization_id )
	{
		$this->organization_id = $organization_id;
		return $this;
	}

	private function getOrganizationID()
	{
		if ( isset( $this->organization_id ) ) {
			return $this->organization_id;
		}

		throw new \Exception( "organization_id not set" );
	}

	public function setInterview( $interview )
	{
		$this->interivew = $interview;
		return $this;
	}

	public function getInterview()
	{
		if ( isset( $this->interview ) ) {
			return $this->interview;
		}

		throw new \Exception( "Interview could not be created" );
	}

	public function setPositionID( $position_id )
	{
		$this->position_id = $position_id;
		return $this;
	}

	private function getPositionID()
	{
		if ( isset( $this->position_id ) ) {
			return $this->position_id;
		}

		throw new \Exception( "position_id not set" );
	}

	public function setInterviewTemplateID( $interview_template_id )
	{
		$this->interview_template_id = $interview_template_id;
		return $this;
	}

	private function getInterviewTemplateID()
	{
		if ( isset( $this->interview_template_id ) ) {
			return $this->interview_template_id;
		}

		throw new \Exception( "inteview_template_id not set" );
	}

	public function setStatus( $status )
	{
		if ( in_array( $status, $this->statuses ) ) {
			$this->status = $status;

			return $this;
		}

		throw new \Exception( "Invalid status" );
	}

	private function getStatus()
	{
		return $this->status;
	}

	public function setScheduledTime( $scheduled_time )
	{
		$this->scheduled_time = $scheduled_time;
		return $this;
	}

	private function getScheduledTime()
	{
		return $this->scheduled_time;
	}
}
