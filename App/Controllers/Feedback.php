<?php

namespace Controllers;

use \Core\Controller;

class Feedback extends Controller
{
    public function indexAction()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $logger = $this->load( "logger" );

        if (
            $input->exists() &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "opinion" => [],
                    "subject" => [],
                    "message" => [],
                    "recommendation" => []
                ],
                "feedback"
            )
        ) {
            $logger->info( "feedback form submitted" );
        }

        vdumpd( $inputValidator );
    }
}
