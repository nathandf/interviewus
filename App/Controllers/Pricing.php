<?php

namespace Controllers;

use \Core\Controller;

class Pricing extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $countryRepo = $this->load( "country-repository" );
        $accountRepo = $this->load( "account-repository" );
        $accountUserRepo = $this->load( "account-user-repository" );
        $organizationRepo = $this->load( "organization-repository" );
        $cartRepo = $this->load( "cart-repository" );
        $productRepo = $this->load( "product-repository" );
        $planRepo = $this->load( "plan-repository" );

        $this->user = $userAuth->getAuthenticatedUser();
        $this->account = null;
        $this->organization = null;
        $this->cart = null;

        if ( !is_null( $this->user ) ) {
            $this->account = $accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );
            $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );
        }

        $this->view->assign( "countries", $countryRepo->get( [ "*" ] ) );
        $this->view->assign( "account", $this->account );
        $this->view->assign( "organization", $this->organization );
        $this->view->assign( "user", $this->user );
    }

    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $planRepo = $this->load( "plan-repository" );
        $planDetailsRepo = $this->load( "plan-details-repository" );

        $plans = $planRepo->get( [ "*" ] );

        foreach ( $plans as $plan ) {
            $plan->details = $planDetailsRepo->get( [ "*" ], [ "plan_id" => $plan->id ], "single" );
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "add_to_cart" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "plan_id" => [
                        "required" => true,
                        "in_array" => $planRepo->get( [ "id" ], [], "raw" )
                    ],
                    "billing_frequency" => [
                        "required" => true,
                        "in_array" => [ "annually", "monthly" ]
                    ]
                ],
                "add_to_cart"
            )
        ) {
            if ( !is_null( $this->user ) ) {
                $cartRepo = $this->load( "cart-repository" );
                $productRepo = $this->load( "product-repository" );

                // Get existing cart
                $cart = $cartRepo->get( [ "*" ], [ "account_id" => $this->account->id ], "single" );

                // If no cart exists, create a new one
                if ( is_null( $cart ) ) {
                    $cart = $cartRepo->insert([
                        "account_id" => $this->account->id
                    ]);
                }

                // Get all products for this cart
                $cart->products = $productRepo->get( [ "*" ], [ "cart_id" => $cart->id ] );

                // Update all products in cart
                foreach ( $cart->products as $product ) {
                    $productRepo->delete( [ "id" ], [ $product->id ] );
                }

                // Add new product to the cart
                $product = $productRepo->insert([
                    "cart_id" => $cart->id,
                    "plan_id" => $this->request->post( "plan_id" ),
                    "billing_frequency" => $this->request->post( "billing_frequency" )
                ]);

                $this->view->redirect( "cart/" );
            }

            $requestValidator->addError( "add_to_cart", "Invalid Action. You must be logged in." );
        }

        $this->view->assign( "plans", $plans );
        $this->view->assign( "error_messages", $requestValidator->getErrors() );

        $this->view->setTemplate( "pricing/index.tpl" );
        $this->view->render( "App/Views/Index.php" );
    }

    public function signIn()
    {
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "sign_in" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "email" => [
                        "required" => true,
                        "email" => true
                    ],
                    "password" => [
                        "required" => true,
                        "min" => 3
                    ]
                ],
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
                [
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

            return [ "SignUp:createAccount", "SignUp:createAccountAjax", null, null ];
        }

        return [ null, "SignUp:createAccountAjaxError", null, [ "errors" => $requestValidator->errors[ "create_account" ] ] ];
    }
}
