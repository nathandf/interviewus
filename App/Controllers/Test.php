<?php

namespace Controllers;

use \Core\Controller;

class Test extends Controller
{
    public function indexAction()
    {
        $isr = $this->load( "inbound-sms-repository" );
        $smses = $isr->get( [ "*" ] );
        $isc = $this->load( "inbound-sms-concatenator" );

        foreach ( $smses as $sms ) {
            $isc->concatenate( $sms );
        }
    }
}
