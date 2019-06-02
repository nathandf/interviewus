<?php

namespace Model\Services;

use Contracts\SMSMessagerInterface;

class ConversationCoordinator
{
	private $phoneRepo;
	private $twilioPhoneNumberRepo;
	private $interview_type_id;
	private $interview_type;
	private $interview;

	public function __construct(
		PhoneRepository $phoneRepo,
		TwilioPhoneNumberRepository $twilioPhoneNumberRepo
	) {
		$this->phoneRepo = $phoneRepo;
		$this->twilioPhoneNumberRepo = $twilioPhoneNumberRepo;
	}

	// Create conversation
	public function create( $phone_id )
	{
		return null;
	}
}
