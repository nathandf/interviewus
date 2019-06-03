<?php

namespace Model\Services;

use Contracts\SMSMessagerInterface;

class ConversationProvisioner
{
	private $twilioPhoneNumberRepo;
	private $conversationRepo;
	private $conversation_limit = 2; // Arbitrary for now

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
		$twilioPhoneNumbers = $this->twilioPhoneNumberRepo->get( [ "*" ] );

		// Get all of the ids of all conversations currently ongoing with the number provided
		$currentConversations = $this->conversationRepo->get(
			[ "*" ],
			[ "e164_phone_number" => $e164_phone_number ]
		);

		// If no conversations exist, create one with any twilio number
		if ( empty( $currentConversations ) ) {

			$conversation = $this->conversationRepo->insert([
				"twilio_phone_number_id" => $twilioPhoneNumbers[ rand( 0, ( count( $twilioPhoneNumbers ) - 1 ) ) ],
				"e164_phone_number" => $e164_phone_number
			]);

			return $conversation;
		}

		if ( count( $currentConversations ) < $this->conversation_limit ) {

			// Create a list of unavailable twilio phone number ids
			$unavailable_twilio_phone_number_ids = [];
			foreach ( $currentConversations as $_conversation ) {
				$unavailable_twilio_phone_number_ids[] = $_conversation->twilio_phone_number_id;
			}

			// Get all twilio phone numbers available for conversation
			$availableTwilioNumbers = $this->twilioPhoneNumberRepo->getAvailable(
				$unavailable_twilio_phone_number_ids
			);

			// Create a conversation
			$conversation = $this->conversationRepo->insert([
				"twilio_phone_number_id" => $availableTwilioNumbers[ rand( 0, ( count( $availableTwilioNumbers ) - 1 ) ) ]->id,
				"e164_phone_number" => $e164_phone_number
			]);

			return $conversation;
		}

		throw new \Exception( "Conversation limit reached. No more conversations can be created for phone number '{$e164_phone_number}'" );
	}
}
