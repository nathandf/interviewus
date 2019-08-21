<?php

namespace Controllers;

use \Core\Controller;

class PasswordReset extends Controller
{
    public function resetAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $logger = $this->load( "logger" );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "reset_password" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "password" => [
                        "required" => true
                    ]
                ],
                "reset_password"
            )
        ) {
            return [ "PasswordReset:reset", "DefaultView:redirect", null, "sign-in" ];
        }
    }
}
