<?php

namespace Controllers;

use \Core\Controller;

class Cart extends Controller
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

        if ( is_null( $this->user ) ) {
            $this->view->redirect( "" );
        }

        $this->account = $accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );

        $this->cart = $cartRepo->get( [ "*" ], [ "account_id" => $this->account->id ], "single" );
        if ( is_null( $this->cart ) ) {
            $this->view->redirect( "pricing/" );
        }

        // Get all products for cart
        $this->cart->products = $productRepo->get( [ "*" ], [ "cart_id" => $this->cart->id ] );

        // Get plan details for this product
        foreach ( $this->cart->products as $product ) {
            $product->plan = $planRepo->get( [ "*" ], [ "id" => $product->plan_id ], "single" );
        }

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );

        $this->view->assign( "countries", $countryRepo->get( [ "*" ] ) );
        $this->view->assign( "account", $this->account );
        $this->view->assign( "organization", $this->organization );
        $this->view->assign( "user", $this->user );
        $this->view->assign( "cart", $this->cart );
    }

    public function indexAction()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $braintreeClientTokenGenerator = $this->load( "braintree-client-token-generator" );

        if (
            $input->exists( "get" ) &&
            $input->issetField( "purchase" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "payment_method_nonce" => [
                        "required" => true
                    ]
                ],
                "purchase"
            )
        ) {
            $braintreeSubscriptionRepo = $this->load( "braintree-subscription-repository" );

            // Create a subscription with braintree api using payment nonce
            // provided by braintree DropinUI
            $result = $braintreeSubscriptionRepo->create(
                $input->get( "payment_method_nonce" ),
                $this->cart->products[ 0 ]->plan->braintree_plan_id
            );

            // If subscription successful, upgrade and provision account, destroy
            // cart and related products, and save the payment method info
            if ( $result->success ) {
                $accountUpgrader = $this->load( "account-upgrader" );
                $cartDestroyer = $this->load( "cart-destroyer" );
                $planRepo = $this->load( "plan-repository" );

                // Upgrade account
                $accountUpgrader->upgrade( $this->account->id, $this->cart->products[ 0 ]->plan->id );

                // Update braintree subscription id in account
                $accountRepo = $this->load( "account-repository" );
                $accountRepo->update(
                    [ "braintree_subscription_id" => $result->subscription->id ],
                    [ "id" => $this->account->id ]
                );

                // Destroy cart and related products
                $cartDestroyer->destroy( $this->cart->id );

                $this->view->redirect( "profile/" );
            }

            $inputValidator->addError( "purchase", $result->message );
        }

        $this->view->assign( "error_messages", $inputValidator->getErrors() );
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );
        $this->view->assign( "flash_messages", $this->session->getFlashMessages() );
        $this->view->assign(
            "client_token",
            $braintreeClientTokenGenerator->generate(
                $this->account->braintree_customer_id
            )
        );

        $this->view->setTemplate( "cart/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
