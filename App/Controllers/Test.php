<?php

namespace Controllers;

use \Core\Controller;

class Test extends Controller
{
    public function indexAction()
    {
        $cp = $this->load( "conversation-provisioner" );
        vdumpd( $cp->provision( "+18122763172" ) );
    }
}
