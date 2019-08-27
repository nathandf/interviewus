<?php

namespace Model\Services\TwilioAPI;

use Contracts\SMSMessagerInterface;

class SMSMessager implements SMSMessagerInterface
{
    private $clientInitializer;
    private $client;
    private $recipient_country_code;
    private $recipient_national_number;
    private $recipient_E164_phone_number = null;
    private $sender_country_code;
    private $sender_national_number;
    private $sender_E164_phone_number = null;
    private $sms_body;

    public function __construct(
        ClientInitializer $clientInitializer
    ){
        $this->clientInitializer = $clientInitializer;
        $this->client = $this->clientInitializer->init();
    }

    public function send()
    {
        // Send using E164 formatted phone numbers if not null
        if (
            !is_null( $this->recipient_E164_phone_number ) &&
            !is_null( $this->sender_E164_phone_number )
        ) {
            $message = $this->client->messages->create(
                $this->recipient_E164_phone_number,
                [
                    "from" => $this->sender_E164_phone_number,
                    "body" => $this->getSMSBody(),
                    "statusCallback" => $this->clientInitializer->getStatusCallback()
                ]
            );

            return $message;
        }

        $message = $this->client->messages->create(
            $this->getRecipientFullPhoneNumber(),
            [
                "from" => $this->getSenderFullPhoneNumber(),
                "body" => $this->getSMSBody(),
                "statusCallback" => $this->clientInitializer->getStatusCallback()
            ]
        );

        return $message;
    }

    public function setRecipientCountryCode( $country_code )
    {
        $this->recipient_country_code = $country_code;
        return $this;
    }

    public function getRecipientCountryCode()
    {
        if ( isset( $this->recipient_country_code ) === false ) {
            throw new \Exception( "Recipient country code is not set" );
        }

        return $this->recipient_country_code;
    }

    public function setRecipientNationalNumber( $number )
    {
        $this->recipient_national_number = $number;
        return $this;
    }

    public function getRecipientNationalNumber()
    {
        if ( isset( $this->recipient_national_number ) === false ) {
            throw new \Exception( "Recipient national number is not set" );
        }

        return $this->recipient_national_number;
    }

    public function setSenderCountryCode( $country_code )
    {
        $this->sender_country_code = $country_code;
        return $this;
    }

    public function getSenderCountryCode()
    {
        if ( isset( $this->sender_country_code ) === false ) {
            throw new \Exception( "Sender country code is not set" );
        }

        return $this->sender_country_code;
    }

    public function setSenderNationalNumber( $number )
    {
        $this->sender_national_number = $number;
        return $this;
    }

    public function getSenderNationalNumber()
    {
        if ( isset( $this->sender_national_number ) === false ) {
            throw new \Exception( "Sender national number is not set" );
        }

        return $this->sender_national_number;
    }

    public function setSMSBody( $body )
    {
        $this->sms_body = html_entity_decode( $body );
        return $this;
    }

    public function getSMSBody()
    {
        if ( isset( $this->sms_body ) === false ) {
            throw new \Exception( "SMS Body is not set" );
        }

        return $this->sms_body;
    }

    public function getRecipientFullPhoneNumber()
    {
        if ( !is_null( $this->recipient_E164_phone_number ) ) {

            return $this->recipient_E164_phone_number;
        }

        return "+" . $this->getRecipientCountryCode() . $this->getRecipientNationalNumber();
    }

    public function getSenderFullPhoneNumber()
    {
        if ( !is_null( $this->sender_E164_phone_number ) ) {

            return $this->sender_E164_phone_number;
        }

        return "+" . $this->getSenderCountryCode() . $this->getSenderNationalNumber();
    }

    public function setRecipientE164PhoneNumber( $phone_number )
    {
        $this->recipient_E164_phone_number = $phone_number;
        return $this;
    }

    public function setSenderE164PhoneNumber( $phone_number )
    {
        $this->sender_E164_phone_number = $phone_number;
        return $this;
    }
}
