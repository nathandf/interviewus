<?php

namespace Controllers;

use \Core\Controller;

class Pricing extends Controller
{
    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $planRepo = $this->load( "plan-repository" );
        $userAuth = $this->load( "user-authenticator" );

        $this->user = $userAuth->getAuthenticatedUser();

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "add_to_cart" ) != "" &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\AddToCart(
                    $this->request->session( "csrf-token" ),
                    $planRepo->get( [ "id" ], [], "raw" )
                ),
                "add_to_cart"
            )
        ) {
            if ( !is_null( $this->user ) ) {

                return [ "Pricing:addToCart", "Home:redirect", null, "cart/" ];
            }

            $requestValidator->addError( "add_to_cart", "Invalid Action. You must be logged in." );
        }

        return [ "Pricing:index", "Pricing:index", null, [ "errors" =>  $requestValidator->getErrors() ] ];
    }

    public function signIn()
    {
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "sign_in" ) != "" &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\SignIn( $this->request->session( "csrf-token" ) ),
                "ajax_sign_in"
            )
        ) {

            return [ "Home:authenticateUser", "Home:authenticateUserAjax", null, null ];
        }

        return [ null, "Home:signInAjax", null, [ "errors" => $requestValidator->errors[ "ajax_sign_in" ] ] ];
    }

    public function createAccount()
    {
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "create_account" ) != "" &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\Account( $this->request->session( "csrf-token" ) ),
                "create_account"
            )
        ) {

            return [ "SignUp:createAccount", "SignUp:createAccountAjax", null, null ];
        }

        return [ null, "SignUp:createAccountAjaxError", null, [ "errors" => $requestValidator->errors[ "create_account" ] ] ];
    }
}
