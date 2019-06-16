<?php

namespace Model\Services\TwilioAPI;

use Twilio\Twiml;
use Conf\Config;

class PhoneNumberBuyer
{
    private $clientInitializer;
    private $client;
    public $twilio_phone_number_instance;
    public $environment;
    public $configs;

    public function __construct(
        ClientInitializer $clientInitializer,
        Config $config
    ){
        $this->clientInitializer = $clientInitializer;
        $this->client = $clientInitializer->init();
        $this->environment = $config->getEnv();
        $this->configs = $config->configs[ "twilio" ];
    }

    public function getNumber( $iso )
    {
        try {
            $number = $this->client
                ->availablePhoneNumbers( $iso )->local
                ->read()[ 0 ];
        } catch (\Exception $e) {

            return null;
        }

        return $number;
    }

    public function quickBuy( $iso = "US" )
    {
        if ( $this->environment == "production" ) {
            // Purchase the first number on the list.
            $number = $this->getNumber( $iso );

            if ( !is_null( $number ) ) {
                $number = $this->client->incomingPhoneNumbers->create([
                    "phoneNumber" => $number->phoneNumber,
                ]);

                $number = $this->provision( $number );

                return $number;
            }

            return null;
        }

        // Development only
        $number = new \stdClass;
        $number->sid = "PNb3e9c12b31f5a9923eb9befb32bcef32";
        $number->phoneNumber = "+18327694054";
        $number->friendlyName = "(832) 769-4054";

        return $number;
    }

    public function provision( $phone_number_instance )
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
}
