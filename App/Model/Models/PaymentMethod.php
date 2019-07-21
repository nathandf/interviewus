<?php

namespace Model\Models;

use Core\Model;

class PaymentMethod extends ProfileModel
{
	public $errors = [];

	public function create()
	{
		if ( $this->validateAccount() ) {
			$braintreePaymentMethodRepo = $this->load( "braintree-payment-method-repository" );
			$result = $braintreePaymentMethodRepo->create(
                $this->account->braintree_customer_id,
                $this->request->get( "payment_method_nonce" )
            );

            if ( $result->success ) {
                // Save this payment method and make it default if a payment
                // method doesn't exist with this token
				$paymentMethodRepo = $this->load( "payment-method-repository" );
                if (
                    empty(
                        $paymentMethodRepo->get(
                            [ "*" ],
                            [
                                "braintree_payment_method_token" => $result->paymentMethod->token,
                                "account_id" => $this->account->id
                            ],
                            "raw"
                        )
                    )
                ) {
                    $paymentMethodRepo->insert([
                        "account_id" => $this->account->id,
                        "braintree_payment_method_token" =>  $result->paymentMethod->token
                    ]);

                    // Unset all payment methods from default
                    $paymentMethodRepo->update(
                        [ "is_default" => 0 ],
                        [ "account_id" => $this->account->id ]
                    );

                    // Set the new payment method as default
                    $paymentMethodRepo->update(
                        [ "is_default" => 1 ],
                        [ "braintree_payment_method_token" => $result->paymentMethod->token ]
                    );

                    // If a subscription exists, make this payment method the default
					$braintreeSubscriptionRepo = $this->load( "braintree-subscription-repository" );
                    if ( !is_null( $this->account->braintree_subscription_id ) ) {
                        $braintreeSubscriptionRepo->updatePaymentMethod(
                            $this->account->braintree_subscription_id,
                            $result->paymentMethod->token
                        );
                    }

                    $this->request->addFlashMessage( "success", "Payment Method Added" );
                    $this->request->setFlashMessages();
                }

                return;
            }

			$this->request->addFlashMessage( "error", $result->message );
			$this->request->setFlashMessages();
		}
	}

	public function updateDefault()
	{
		if ( $this->validateAccount() ) {
			// Update payment method for this customer in braintree API
			$braintreeCustomerRepo = $this->load( "braintree-customer-repository" );
            $result = $braintreeCustomerRepo->updateDefaultPaymentMethod(
                $this->account->braintree_customer_id,
                $this->request->post( "braintree_payment_method_token" )
            );

            if ( !is_null( $result ) ) {
                // Unset all payment methods from default
				$paymentMethodRepo = $this->load( "payment-method-repository" );
                $paymentMethodRepo->update(
                    [ "is_default" => 0 ],
                    [ "account_id" => $this->account->id ]
                );

                // Set the new payment method as default
                $paymentMethodRepo->update(
                    [ "is_default" => 1 ],
                    [ "braintree_payment_method_token" => $this->request->post( "braintree_payment_method_token" ) ]
                );

                // If a subscription exists, make this payment method the default
                if ( !is_null( $this->account->braintree_subscription_id ) ) {
					$braintreeSubscriptionRepo = $this->load( "braintree-subscription-repository" );
                    $braintreeSubscriptionRepo->updatePaymentMethod(
                        $this->account->braintree_subscription_id,
                        $this->request->post( "braintree_payment_method_token" )
                    );
                }

                $this->request->addFlashMessage( "success", "Default payment method updated" );
                $this->request->setFlashMessages();

				return;
            }

			$this->errors[ "update_default_payment_method" ] = "Invalid Payment Method";

			return;
		}
	}

	public function delete()
	{
		if ( $this->validateAccount() ) {
			$braintreePaymentMethodRepo = $this->load( "braintree-payment-method-repository" );
			if ( $braintreePaymentMethodRepo->delete(
                    $this->request->post(
                        "braintree_payment_method_token"
                    )
                )
            ) {
				$paymentMethodRepo = $this->load( "payment-method-repository" );
                $paymentMethodRepo->delete(
                    [ "braintree_payment_method_token", "is_default" ],
                    [ $this->request->post( "braintree_payment_method_token" ), 0 ]
                );
            }

            $this->request->addFlashMessage( "success", "Payment Method Deleted" );
            $this->request->setFlashMessages();
		}
	}
}
