<?php

namespace Controllers;

use \Core\Controller;

class Feedback extends Controller
{
    public function indexAction()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $mailer = $this->load( "mailer" );
        $emailBuilder = $this->load( "email-builder" );
        $domainObjectFactory = $this->load( "domain-object-factory" );

        if (
            $input->exists() &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "user" => [
                        "required" => true
                    ],
                    "account" => [
                        "required" => true
                    ],
                    "opinion" => [
                        "required" => true
                    ],
                    "subject" => [
                        "required" => true
                    ],
                    "message" => [
                        "required" => true
                    ],
                    "recommendation" => [
                        "required" => true
                    ]
                ],
                "feedback"
            )
        ) {
            $emailContext = $domainObjectFactory->build( "EmailContext" );
            $emailContext->addProps([
                "user" => $input->get( "user" ),
                "account" => $input->get( "account" ),
                "opinion" => $input->get( "opinion" ),
                "subject" => $input->get( "subject" ),
                "message" => $input->get( "message" ),
                "recommendation" => $input->get( "recommendation" )
            ]);

            // Notify admin of user feedback
            $resp = $mailer->setTo( "interview.us.app@gmail.com", "InterviewUs" )
                ->setFrom( "noreply@interviewus.net" )
                ->setSubject( "User Feedback Notification | {$input->get( "subject" )}" )
                ->setContent( $emailBuilder->build( "user-feedback.html", $emailContext ) )
                ->mail();
        }
    }
}
