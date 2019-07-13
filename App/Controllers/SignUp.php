<?php

namespace Controllers;

use \Core\Controller;

class SignUp extends Controller
{
    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );

        // Form validation
        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "create_account" => [
                        "required" => true
                    ],
                    "name" => [
                        "required" => true
                    ],
                    "email" => [
                        "required" => true,
                        "email" => true
                    ],
                    "password" => [
                        "required" => true,
                        "min" => 6
                    ]
                ],
                "create_account"
            )
        ) {
            return [ "SignUp:createAccount", "Signup:createAccount", null, null ];
        }

        return [ null, "SignUp:index", null, [ "error_messages" => $requestValidator->getErrors() ] ];
    }
}
