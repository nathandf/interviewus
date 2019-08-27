<?php

namespace Controllers;

use \Core\Controller;

class Home extends Controller
{
    public function indexAction()
    {
        return [ null, "Home:index", null, null ];
    }

    public function signInAction()
    {
        $userAuth = $this->load( "user-authenticator" );
        $requestValidator = $this->load( "request-validator" );

        if ( !is_null( $userAuth->getAuthenticatedUser() ) ) {
            return [ null, "Home:redirect", null, "profile/" ];
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "sign_in" ) != "" &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\SignIn( $this->request->session( "csrf-token" ) ),
                "sign_in"
            )
        ) {
            return [ "Home:authenticateUser", "Home:authenticateUser", null, null ];
        }

        return [ null, "Home:signIn", null, [ "error_messages" => $requestValidator->getErrors() ] ];
    }

    public function resetPasswordAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $logger = $this->load( "logger" );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "send_reset_link" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "email" => [
                        "required" => true,
                        "email" => true
                    ]
                ],
                "send_reset_link"
            )
        ) {
            $logger->info( "(REQUEST PASSWORD RESET) [IP] {$this->request->ip()} [EMAIL] {$this->request->post( "email" )}" );

            return [ "Home:sendResetLink", "DefaultView:redirect", null, "reset-password" ];
        }

        return [ null, "Home:resetPassword", null, $requestValidator->getErrors() ];
    }

    public function privacyPolicyAction()
    {
        return [ null, "Home:privacyPolicy", null, null ];
    }

    public function termsAndConditionsAction()
    {
        return [ null, "Home:termsAndConditions", null, null ];
    }
}
