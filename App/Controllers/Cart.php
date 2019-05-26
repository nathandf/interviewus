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
            // If a subscription exists, update it. If not, create a subscription
            // using payment method nonce
            $braintreeSubscriptionRepo = $this->load( "braintree-subscription-repository" );

            if ( !is_null( $this->account->braintree_subscription_id ) ) {
                // Create a braintree payment method. If this payment method already
                // exists, it will return the payment method details. If it doesn't,
                // it will create a new payment method for this customer
                $braintreePaymentMethodRepo = $this->load( "braintree-payment-method-repository" );
                $paymentMethodResult = $braintreePaymentMethodRepo->create(
                    $this->account->braintree_customer_id,
                    $input->get( "payment_method_nonce" )
                );

                $result = $braintreeSubscriptionRepo->updatePlan(
                    $this->account->braintree_subscription_id,
                    $paymentMethodResult->paymentMethod->token,
                    $this->cart->products[ 0 ]->plan->braintree_plan_id,
                    $this->cart->products[ 0 ]->plan->price
                );
            } else {
                $result = $braintreeSubscriptionRepo->create(
                    $input->get( "payment_method_nonce" ),
                    $this->cart->products[ 0 ]->plan->braintree_plan_id
                );
            }

            // If subscription successful, upgrade and provision account, destroy
            // cart and related products, and save the payment method info
            if ( $result->success ) {
                // Save this payment method and make it default if a payment
                // method doesn't exist with this token
                $paymentMethodRepo = $this->load( "payment-method-repository" );

                if (
                    empty(
                        $paymentMethodRepo->get(
                            [ "*" ],
                            [
                                "braintree_payment_method_token" => $result->subscription->paymentMethodToken,
                                "account_id" => $this->account->id
                            ]
                        )
                    )
                ) {
                    // Create a native payment method for this subscription
                    $paymentMethodRepo->insert([
                        "account_id" => $this->account->id,
                        "braintree_payment_method_token" =>  $result->subscription->paymentMethodToken,
                    ]);

                    // Unset all payment methods from default
                    $paymentMethodRepo->update(
                        [ "is_default" => 0 ],
                        [ "account_id" => $this->account->id ]
                    );

                    // Set the new payment method as default
                    $paymentMethodRepo->update(
                        [ "is_default" => 1 ],
                        [ "braintree_payment_method_token" => $result->subscription->paymentMethodToken ]
                    );
                }
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

            $error_codes = [];
            foreach( $result->errors->deepAll() as $error ) {
                $error_codes[] = $error->code;
            }

            // If billing frequency error, add additonal error message
            $additional_message = null;
            if ( in_array( 91922, $error_codes ) ) {
                $additional_message = "Cancel your current subscription and try again.";
            }

            $inputValidator->addError( "purchase", $result->message . " " . $additional_message );
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
