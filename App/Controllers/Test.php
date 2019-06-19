<?php

namespace Controllers;

use \Core\Controller;

class Test extends Controller
{
    public function indexAction()
    {
        // Send welcome and confirmation email
        $mailer = $this->load( "mailer" );
        $emailBuilder = $this->load( "email-builder" );
        $domainObjectFactory = $this->load( "domain-object-factory" );

        $emailContext = $domainObjectFactory->build( "EmailContext" );
        $emailContext->addProps([
            "first_name" => "Nate"
        ]);

        $resp = $mailer->setTo( "interview.us.app@gmail.com", "Nate" )
            ->setFrom( "getstarted@interviewus.net", "InterviewUs" )
            ->setSubject( "Here's 9 Free interviews on Us. Welcome to InterviewUs!" )
            ->setContent( $emailBuilder->build( "welcome-email.html", $emailContext ) )
            ->mail();
    }
}
