<?php

namespace Model\Services\TwilioAPI;

use Twilio\Twiml;

class PhoneNumberBuyer
{
    private $clientInitializer;
    private $client;
    public $twilio_phone_number_instance;

    public function __construct(
        ClientInitializer $clientInitializer
    ){
        $this->clientInitializer = $clientInitializer;
        $this->client = $clientInitializer->init();
    }

    public function buy( $twilio_phone_number_instance )
    {
        die( "delete this die to purchase" );
        // Purchase the first number on the list.
        $number = $this->client->incomingPhoneNumbers
            ->create(
                [
                    "phoneNumber" => $twilio_phone_number_instance->phoneNumber,
                ]
            );

        $number = $this->configure( $number );

        return $number;
    }

    public function configure( $phone_number_instance )
    {
        $number = $this->client->incomingPhoneNumbers( $phone_number_instance->sid )
            ->update(
                [
                    "smsMethod" => "POST",
                    "smsUrl" => "https://interviewus.net/webhooks/twilio/{$phone_number_instance->sid}/incoming/sms",
                    "voiceMethod" => "POST",
                    "voiceUrl" => "https://interviewus.net/webhooks/twilio/{$phone_number_instance->sid}/incoming/voice"
                ]
            );

        return $number;
    }

    public function fetchByPhoneNumber( $twilio_phone_number )
    {
        // Purchase the first number on the list.
        $number = $this->client->incomingPhoneNumbers
            ->create(
                [
                    "phoneNumber" => $twilio_phone_number,
                ]
            );

        return $number;
    }

    // Arg twilio_phone_number should be E164 Formatted
    public function updateByPhoneNumber( $twilio_phone_number )
    {
        $number = $this->fetchByPhoneNumber( $twilio_phone_number );
        $number = $this->configure( $number );

        return $number;
    }

}
