<?php

namespace Model\Services;

use Contracts\SMSMessagerInterface;

class ConversationProvisioner
{
	private $twilioPhoneNumberRepo;
	private $conversationRepo;
	private $conversation_limit;

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

		$this->setConversationLimit( count( $twilioPhoneNumbers ) );

		// Get all conversations currently ongoing with the number provided
		$currentConversations = $this->conversationRepo->get(
			[ "*" ],
			[ "e164_phone_number" => $e164_phone_number ]
		);

		// If no conversations exist, create one with any twilio number
		if ( empty( $currentConversations ) ) {

			// Choose a random number from existing twilio numbers. NOTE It would
			// be better the twilio number were chosen based on the number on
			// conversations it's involed with in such a way that all conversations
			// are distributed roughly evenly among the twilio numbers.
			$conversation = $this->conversationRepo->insert([
				"twilio_phone_number_id" => $twilioPhoneNumbers[ rand( 0, ( count( $twilioPhoneNumbers ) - 1 ) ) ]->id,
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

			if ( !empty( $availableTwilioNumbers ) ) {
				// Create a conversation
				$conversation = $this->conversationRepo->insert([
					"twilio_phone_number_id" => $availableTwilioNumbers[ rand( 0, ( count( $availableTwilioNumbers ) - 1 ) ) ]->id,
					"e164_phone_number" => $e164_phone_number
				]);

				return $conversation;
			}

			throw new \Exception( "No more phone numbers available" );
		}

		throw new \Exception( "Conversation limit reached. No more conversations can be created for phone number '{$e164_phone_number}'" );
	}

	private function setConversationLimit( $conversation_limit )
	{
		$this->conversation_limit = $conversation_limit;
		return $this;
	}

	public function getConversationLimit()
	{
		if ( isset( $this->conversation_limit ) === false ) {
			throw new \Exception( "No conversation limit set" );
		}

		return $this->conversation_limit;
	}
}
