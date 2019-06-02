<?php

namespace Model\Services;

use Contracts\SMSMessagerInterface;

class ConversationCoordinator
{
	private $phoneRepo;
	private $twilioPhoneNumberRepo;
	private $conversationRepo;
	private $interview_type_id;
	private $interview_type;
	private $interview;

	public function __construct(
		ConversationRepository $conversationRepo,
		PhoneRepository $phoneRepo,
		TwilioPhoneNumberRepository $twilioPhoneNumberRepo
	) {
		$this->phoneRepo = $phoneRepo;
		$this->twilioPhoneNumberRepo = $twilioPhoneNumberRepo;
		$this->conversationRepo = $conversationRepo;
	}

	// Create conversation
	public function start( $phone_id )
	{
		$phone = $this->phoneRepo->get( [ "*" ], [ "id" => $phone_id ], "single" );
	}
}
