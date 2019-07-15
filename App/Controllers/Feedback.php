<?php

namespace Controllers;

use \Core\Controller;

class Feedback extends Controller
{
    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );

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
            return [ "Feedback:send", "Feedback:send", null, null ];
        }
    }
}
