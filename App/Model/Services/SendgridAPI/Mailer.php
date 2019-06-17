<?php

namespace Model\Services\SendgridAPI;

use Contracts\MailerInterface;
use Model\Services\UnsubscribeRepository;

class Mailer implements MailerInterface
{
    private $client;
    private $unsubscribeRepo;
    private $from;
    private $to;
    private $subject;
    private $content;
    private $content_types = [ "text/html", "text/plain" ];

    public function __construct(
        ClientInitializer $clientInitializer,
        UnsubscribeRepository $unsubscribeRepo
    ) {
        $this->clientInitializer = $clientInitializer;
        $this->client = $this->clientInitializer->init();
        $this->unsubscribeRepo = $unsubscribeRepo;
    }

    public function mail()
    {
        if ( $this->clientInitializer->getEnv() != "production" ) {
            $this->setTo( "interview.us.app@gmail.com", "Testy To" );
            $this->setFrom( "noreplydev@interviewus.net", "Testy From" );
        }

        $mail = new \SendGrid\Mail(
            $this->getFrom(),
            $this->getSubject(),
            $this->getTo(),
            $this->getContent()
        );
        
        $response = $this->client->mail()->send()->post( $mail );

        return $response;
    }

    public function setFrom( $email_address, $name = "" )
    {
        $this->from = new \SendGrid\Email( $name, $email_address );

        return $this;
    }

    private function getFrom()
    {
        if ( isset( $this->from ) === false ) {
            throw new \Exception( "'From' property has not been set" );
        }

        return $this->from;
    }

    public function setTo( $email_address, $name="" )
    {
        if ( $this->isUnsubscribed( trim( strtolower( $email_address ) ) ) ) {
            throw new \Exception( "Cannot send email to {$email_address}. They are unsubscribed." );
        }

        $this->to = new \SendGrid\Email( $name, $email_address );

        return $this;
    }

    private function getTo()
    {
        if ( isset( $this->to ) === false ) {
            throw new \Exception( "'To' property has not been set" );
        }

        return $this->to;
    }

    public function setContent( $content, $content_type = "text/html" )
    {
        if ( !in_array( $content_type, $this->content_types ) ) {
            throw new \Exception( "content_type provided is invalid: '{$content_type}'" );
        }

        $this->content = new \SendGrid\Content( $content_type, $content );

        return $this;
    }

    private function getContent()
    {
        if ( isset( $this->content ) === false ) {
            throw new \Exception( "'Content' property has not been set" );
        }

        return $this->content;
    }

    public function setSubject( $subject )
    {
        $this->subject = $subject;

        return $this;
    }

    private function getSubject()
    {
        if ( isset( $this->subject ) === false ) {
            throw new \Exception( "'Subject' property has not been set" );
        }

        return $this->subject;
    }

    private function isUnsubscribed( $email_address )
    {
        if ( in_array( $email_address, $this->unsubscribeRepo->get( [ "email" ], [], "raw" ) ) ) {
            return true;
        }

        return false;
    }
}
