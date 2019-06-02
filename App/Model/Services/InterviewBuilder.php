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
	private $deployment_type_id;
	private $interview_type;
	private $interview_type_account_property;
	private $interview;
	private $interview_template_id;
	private $position_id;
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
		IntervieweeRepository $intervieweeRepo
	) {
		$this->accountRepo = $accountRepo;
		$this->interviewRepo = $interviewRepo;
		$this->questionRepo = $questionRepo;
		$this->interviewQuestionRepo = $interviewQuestionRepo;
		$this->intervieweeRepo = $intervieweeRepo;
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

			$interview = $this->interviewRepo->insert([
				"deployment_type_id" => $this->getDeploymentTypeID(),
				"organization_id" => $this->getOrganizationID(),
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

			return $interview;
		}

		throw new \Exception( "Invalid interviewee" );
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
		if ( !in_array( $deployment_type_id, $this->deployment_type_ids ) ) {
			throw new \Exception( "Invalid deployment_type_id" );
		}

		$this->deployment_type_id = $deployment_type_id;
		return $this;
	}

	public function getDeploymentTypeID()
	{
		if ( isset( $this->deployment_type_id ) == false ) {
			throw new \Exception( "deployment_id not set" );
		}

		return $this->deployment_type_id;
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
