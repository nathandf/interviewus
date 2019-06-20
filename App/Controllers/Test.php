<?php

namespace Controllers;

use \Core\Controller;

class Test extends Controller
{
    public function indexAction()
    {
        // Send interviewee email prompting to start interview
        $mailer = $this->load( "mailer" );
        $emailBuilder = $this->load( "email-builder" );
        $domainObjectFactory = $this->load( "domain-object-factory" );

        $emailContext = $domainObjectFactory->build( "EmailContext" );
        $emailContext->addProps([
            "full_name" => "TestName Interviewee",
            "first_name" => "TestName",
            "interview_token" => "this-is-a-token",
            "sent_by" => "Nathan Freeman"
        ]);

        $resp = $mailer->setTo( "interview.us.app@gmail.com", "TestName" )
            ->setFrom( "noreply@interviewus.net", "InterviewUs" )
            ->setSubject( "You have a pending interivew: TestName Interviewee" )
            ->setContent( $emailBuilder->build( "interview-dispatch-notification.html", $emailContext ) )
            ->mail();
    }
}
