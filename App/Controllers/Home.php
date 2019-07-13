<?php

namespace Controllers;

use \Core\Controller;

class Home extends Controller
{
    public function indexAction()
    {
        return [ null, "Home:index", [] ];
    }

    public function signInAction()
    {
        $userAuth = $this->load( "user-authenticator" );
        $requestValidator = $this->load( "request-validator" );

        if ( !is_null( $userAuth->getAuthenticatedUser() ) ) {
            return [ null, "Home:loginRedirect", [] ];
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "sign_in" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "email" => [
                        "required" => true,
                        "email" => true
                    ],
                    "password" => [
                        "required" => true
                    ],
                ],
                "sign_in"
            )
        ) {
            return [ "Home:authenticateUser", "Home:authenticateUser", [] ];
        }

        return [ null, "Home:signIn", [ "error_messages" => $requestValidator->getErrors() ] ];
    }

    public function privacyPolicyAction()
    {

    }

    public function termsAndConditionsAction()
    {

    }
}
