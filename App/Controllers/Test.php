<?php

namespace Controllers;

use \Core\Controller;

class Test extends Controller
{
    public function indexAction()
    {
        $mailer = $this->load( "mailer" );
        $emailBuilder = $this->load( "email-builder" );
        $domainObjectFactory = $this->load( "domain-object-factory" );

        $emailContext = $domainObjectFactory->build( "EmailContext" );
        $emailContext->addProps([
            "first_name" => "Nathan Freeman"
        ]);
        
        $resp = $mailer->setTo( "interview.us.app@gmail.com", "Nate" )
            ->setFrom( "noreplydev@interviewus.net", "Nathan Freeman" )
            ->setSubject( "HTML Test fiddy" )
            ->setContent( $emailBuilder->build( "welcome-email.html", $emailContext ) )
            ->mail();
    }
}
