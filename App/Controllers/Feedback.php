<?php

namespace Controllers;

use \Core\Controller;

class Feedback extends Controller
{
    public function indexAction()
    {

        $requestValidator = $this->load( "request-validator" );
        $mailer = $this->load( "mailer" );
        $emailBuilder = $this->load( "email-builder" );
        $domainObjectFactory = $this->load( "domain-object-factory" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
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
                "user" => $this->request->post( "user" ),
                "account" => $this->request->post( "account" ),
                "opinion" => $this->request->post( "opinion" ),
                "subject" => $this->request->post( "subject" ),
                "message" => $this->request->post( "message" ),
                "recommendation" => $this->request->post( "recommendation" )
            ]);

            // Notify admin of user feedback
            $resp = $mailer->setTo( "interview.us.app@gmail.com", "InterviewUs" )
                ->setFrom( "noreply@interviewus.net" )
                ->setSubject( "User Feedback Notification | {$this->request->post( "subject" )}" )
                ->setContent( $emailBuilder->build( "user-feedback.html", $emailContext ) )
                ->mail();
        }
    }
}
