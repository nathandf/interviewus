<?php

namespace Controllers;

use \Core\Controller;

class Feedback extends Controller
{
    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $logger = $this->load( "logger" );
        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\Feedback( $this->request->session( "csrf-token" ) ),
                "feedback"
            )
        ) {
            return [ "Feedback:send", "Feedback:send", null, null ];
        }

        $logger->debug( "test" );
    }
}
