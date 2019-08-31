<?php

namespace Model\Models;

class Incoming extends TwilioIncomingModel
{
	public function sms()
	{
		if ( $this->validateTwilioNumber() ) {
			$interviewRepo = $this->load( "interview-repository" );
	        $conversationRepo = $this->load( "conversation-repository" );
	        $inboundSmsRepo = $this->load( "inbound-sms-repository" );
	        $inboundSmsConcatenator = $this->load( "inbound-sms-concatenator" );

			// Get the conversation
			$conversation = $conversationRepo->get(
				[ "*" ],
				[
					"twilio_phone_number_id" => $this->twilioPhoneNumber->id,
					"e164_phone_number" => $this->request->post( "From" )
				],
				"single"
			);
			
			if ( !is_null( $conversation ) ) {
				$inboundSms = $inboundSmsRepo->insert([
					"conversation_id" => $conversation->id,
					"body" => $this->request->post( "Body" ),
					"recieved_at" => time()
				]);

				$inboundSmsConcatenator->concatenate( $inboundSms );

				return;
			}

			$this->logger->error( "Conversation does not exist between '{$this->twilioPhoneNumber->phone_number}' and '{$this->request->post( "from" )}'" );
		}
	}
}
