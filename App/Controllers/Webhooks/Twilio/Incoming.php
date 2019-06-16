<?php

namespace Controllers\Webhooks\Twilio;

use \Core\Controller;

class Incoming extends Controller
{
    public function before()
    {
        $this->requireParam( "sid" );
        $twilioPhoneNumberRepo = $this->load( "twilio-phone-number-repository" );
        $this->logger = $this->load( "logger" );

        $this->twilioPhoneNumber = $twilioPhoneNumberRepo->get( [ "*" ], [ "sid" => $this->params[ "sid" ] ], "single" );

        // If no twilio phone number exists
        if ( is_null( $this->twilioPhoneNumber ) ) {
            $this->logger->error( "Twilio number with sid '{$this->params[ "sid" ]}' does not exist" );
            die();
            exit();
        }
    }

    public function smsAction()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $interviewRepo = $this->load( "interview-repository" );
        $conversationRepo = $this->load( "conversation-repository" );
        $inboundSmsRepo = $this->load( "inbound-sms-repository" );
        $inboundSmsConcatenator = $this->load( "inbound-sms-concatenator" );

        if (
            $input->exists() &&
            $inputValidator->validate(
                $input,
                [
                    "From" => [
                        "required" => true
                    ],
                    "Body" => [
                        "required" => true
                    ]
                ],
                "recieve_sms"
            )
        ) {
            // Get the conversation
            $conversation = $conversationRepo->get(
                [ "*" ],
                [
                    "twilio_phone_number_id" => $this->twilioPhoneNumber->id,
                    "e164_phone_number" => $input->get( "From" )
                ],
                "single"
            );

            if ( !is_null( $conversation ) ) {
                $inboundSms = $inboundSmsRepo->insert([
                    "conversation_id" => $conversation->id,
                    "body" => $input->get( "Body" ),
                    "recieved_at" => time()
                ]);

                $inboundSmsConcatenator->concatenate( $inboundSms );

                return;
            }

            $this->logger->error( "Conversation does not exist between '{$this->twilioPhoneNumber->phone_number}' and '{$input->get( "from" )}'" );

            return;
        }
    }

    public function voiceAction()
    {

    }
}
