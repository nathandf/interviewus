<?php

namespace Controllers\Webhooks\Twilio;

use \Core\Controller;

class Incoming extends Controller
{

    public function before()
    {
        $this->requireParam( "sid" );
        $organizationRepo = $this->load( "organization-repository" );
        $phoneRepo = $this->load( "phone-repository" );
        $this->twilioServiceDispatcher = $this->load( "twilio-service-dispatcher" );
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
        $intervieweeRepo = $this->load( "interviewee-repository" );
        $interviewRepo = $this->load( "interview-repository" );
        $interviewQuestionRepo = $this->load( "interview-question-repository" );
        $intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
        $phoneRepo = $this->load( "phone-repository" );
        $interviewDispatcher = $this->load( "interview-dispatcher" );
        $conversationRepo = $this->load( "conversation-repository" );

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
                $interview = $interviewRepo->get(
                    [ "*" ],
                    [
                        "conversation_id" => $conversation->id,
                        "status" => "active",
                        "deployment_type_id" => 1
                    ],
                    "single"
                );

                if ( !is_null( $interview ) ) {
                    $interviewDispatcher->answerNextQuestion( $interview, $input->get( "Body" ) );

                    return;
                }
                $this->logger->error( "Interview not found for converation_id '{$conversation->id}'" );

                return;
            }
            $this->logger->error( "Conversation does not exist between '{$this->twilioPhoneNumber->phone_number}' and '{$input->get( "from" )}'" );

            return;
        }
    }

    public function voiceAction()
    {
        // // Forward the call the organization's phone number
        // if ( !is_null( $this->organization_phone ) ) {
        //     $this->twilioServiceDispatcher->forwardCall(
        //         $this->organization_phone->getE164FormattedPhoneNumber()
        //     );
        // }
    }
}
