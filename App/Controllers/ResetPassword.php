<?php

namespace Controllers;

use \Core\Controller;

class ResetPassword extends Controller
{
    public function indexAction()
    {
        try {
            $token = $this->request->params( "token" );
        } catch ( \Exception $e ) {
            return [ null, "Error:e404", null, null ];
        }

        $passwordResetTokenRepo = $this->load( "password-reset-token-repository" );
        $passwordResetToken = $passwordResetTokenRepo->get( [ "*" ], [ "token" => $token ], "single" );

        $passwordResetTokenHandler = $this->load( "password-reset-token-handler" );
        if ( !$passwordResetTokenHandler->validate( $passwordResetToken ) ) {
            return [ null, "DefaultView:redirect", null, "reset-password/invalid-token" ];
        }

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
            $logger->info( "(PASSWORD RESET) [IP] {$this->request->ip()}" );

            return [ "Password:reset", "DefaultView:redirect", null, "sign-in" ];
        }

        return [ null, "ResetPassword:index", null, $requestValidator->getErrors() ];
    }

    public function invalidTokenAction()
    {
        return [ null, "ResetPassword:invalidToken", null, null ];
    }
}
