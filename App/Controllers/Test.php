<?php

namespace Controllers;

use \Core\Controller;

class Test extends Controller
{
    public function indexAction()
    {
        $mailer = $this->load( "mailer" );
        $resp = $mailer->setTo( "interview.us.app@gmail.com", "Nate" )
            ->setFrom( "noreplydev@interviewus.net", "Nate the dev" )
            ->setSubject( "Testing unsubscribe" )
            ->setContent( "This is a test of the unsubscribe footer" )
            ->mail();

        vdumpd( $resp );
    }
}
