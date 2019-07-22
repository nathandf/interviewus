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

    public function privacyPolicyAction()
    {
        return [ null, "Home:privacyPolicy", null, null ];
    }

    public function termsAndConditionsAction()
    {
        return [ null, "Home:termsAndConditions", null, null ];
    }
}
