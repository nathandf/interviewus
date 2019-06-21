<?php

namespace Controllers;

use \Core\Controller;

class Test extends Controller
{
    public function indexAction()
    {
        // Send account upgrade email
        $mailer = $this->load( "mailer" );
        $emailBuilder = $this->load( "email-builder" );
        $domainObjectFactory = $this->load( "domain-object-factory" );

        $emailContext = $domainObjectFactory->build( "EmailContext" );
        $emailContext->addProps([
            "transaction_id" => "{transaction_id}",
            "plan_name" => "{plan name} - {plan id}",
            "billing_frequency" => "{billing frequency}",
            "sub_total" => "{sub total}",
            "total" => "{total}",
            "full_name" => "{full name}",
            "last_4" => "{last 4}",
            "datetime" => date( "c" )
        ]);

        $resp = $mailer->setTo( "interview.us.app@gmail.com", "Nate" )
            ->setFrom( "noreply@interviewus.net", "InterviewUs" )
            ->setSubject( "InterviewUs - Payment Processed Successfully - Account Updated" )
            ->setContent( $emailBuilder->build( "payment-receipt.html", $emailContext ) )
            ->mail();
    }
}
