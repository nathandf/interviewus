<?php

namespace Model\Models;

use Core\Model;

class Cart extends Model
{
	public $errors = [];

	public function index()
	{
		$userAuth = $this->load( "user-authenticator" );
        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            return [ null, "DefaultView:redirect", null, "" ];
        }

        $accountRepo = $this->load( "account-repository" );
        $this->account = $accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );

        $cartRepo = $this->load( "cart-repository" );
        $this->cart = $cartRepo->get( [ "*" ], [ "account_id" => $this->account->id ], "single" );
        if ( is_null( $this->cart ) ) {
            return [ null, "DefaultView:redirect", null, "pricing/" ];
        }

        // Get all products for cart
        $productRepo = $this->load( "product-repository" );
        $this->cart->products = $productRepo->get( [ "*" ], [ "cart_id" => $this->cart->id ] );

        // Get plan details for this product
        $planRepo = $this->load( "plan-repository" );
        foreach ( $this->cart->products as $product ) {
            $product->plan = $planRepo->get( [ "*" ], [ "id" => $product->plan_id ], "single" );
        }

		$braintreeClientTokenGenerator = $this->load( "braintree-client-token-generator" );
		$this->braintreeClientToken = $braintreeClientTokenGenerator->generate(
			$this->account->braintree_customer_id
		);
	}

	public function purchase()
	{
		$userAuth = $this->load( "user-authenticator" );
        $this->user = $userAuth->getAuthenticatedUser();

		$accountRepo = $this->load( "account-repository" );
        $this->account = $accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );

		$cartRepo = $this->load( "cart-repository" );
        $this->cart = $cartRepo->get( [ "*" ], [ "account_id" => $this->account->id ], "single" );

        // Get all products for cart
		$productRepo = $this->load( "product-repository" );
        $this->cart->products = $productRepo->get( [ "*" ], [ "cart_id" => $this->cart->id ] );

        // Get plan details for this product
		$planRepo = $this->load( "plan-repository" );
        foreach ( $this->cart->products as $product ) {
            $product->plan = $planRepo->get( [ "*" ], [ "id" => $product->plan_id ], "single" );
        }

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
                $this->request->get( "payment_method_nonce" )
            );

            $result = $braintreeSubscriptionRepo->updatePlan(
                $this->account->braintree_subscription_id,
                $paymentMethodResult->paymentMethod->token,
                $this->cart->products[ 0 ]->plan->braintree_plan_id,
                $this->cart->products[ 0 ]->plan->price
            );
        } else {
            $result = $braintreeSubscriptionRepo->create(
                $this->request->get( "payment_method_nonce" ),
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

            // Upgrade account
			$accountUpgrader = $this->load( "account-upgrader" );
			$planRepo = $this->load( "plan-repository" );
            $accountUpgrader->upgrade( $this->account, $this->cart->products[ 0 ]->plan->id );

            // Update braintree subscription id in account
            $accountRepo = $this->load( "account-repository" );
            $accountRepo->update(
                [ "braintree_subscription_id" => $result->subscription->id ],
                [ "id" => $this->account->id ]
            );

            // Send account upgrade email
			$domainObjectFactory = $this->load( "domain-object-factory" );
            $emailContext = $domainObjectFactory->build( "EmailContext" );
            $emailContext->addProps([
                "transaction_id" => $result->subscription->transactions[ 0 ]->id,
                "plan_name" => $this->cart->products[ 0 ]->plan->name . " (" . $this->cart->products[ 0 ]->plan->braintree_plan_id . ")",
                "billing_frequency" => $this->cart->products[ 0 ]->billing_frequency,
                "sub_total" => ( $this->cart->products[ 0 ]->billing_frequency == "annually" ) ? $this->cart->products[ 0 ]->plan->price * 12 : $this->cart->products[ 0 ]->plan->price,
                "total" => ( $this->cart->products[ 0 ]->billing_frequency == "annually" ) ? $this->cart->products[ 0 ]->plan->price * 12 : $this->cart->products[ 0 ]->plan->price,
                "full_name" => $this->user->getFullName(),
                "last_4" => $result->subscription->transactions[ 0 ]->creditCard[ "last4" ],
                "datetime" => date( "c" )
            ]);

			$emailBuilder = $this->load( "email-builder" );
			$mailer = $this->load( "mailer" );
            $resp = $mailer->setTo( $this->user->email, $this->user->getFullName() )
                ->setFrom( "noreply@interviewus.net", "InterviewUs" )
                ->setSubject( "InterviewUs - Payment Confirmation" )
                ->setContent( $emailBuilder->build( "payment-confirmation.html", $emailContext ) )
                ->mail();

            // Authenticate and log in User
            $userAuth = $this->load( "user-authenticator" );
            $userAuth->authenticate( $this->user->email, $this->request->get( "password" ) );

            // Destroy cart and related products
			$cartDestroyer = $this->load( "cart-destroyer" );
            $cartDestroyer->destroy( $this->cart->id );

            return;
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

        $this->errors[] = $result->message . " " . $additional_message;
	}
}
