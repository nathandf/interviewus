<?php

namespace Model\Services;

use Contracts\SMSMessagerInterface;

class ConversationProvisioner
{
	private $twilioPhoneNumberRepo;
	private $conversationRepo;

	public function __construct(
		ConversationRepository $conversationRepo,
		TwilioPhoneNumberRepository $twilioPhoneNumberRepo
	) {
		$this->conversationRepo = $conversationRepo;
		$this->twilioPhoneNumberRepo = $twilioPhoneNumberRepo;
	}

	// Create conversation
	public function provision( $e164_phone_number )
	{
		$phone = $this->phoneRepo->get( [ "*" ], [ "id" => $phone_id ], "single" );
	}
}
