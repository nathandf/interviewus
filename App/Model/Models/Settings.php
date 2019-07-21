<?php

namespace Model\Models;

use Core\Model;

class Settings extends ProfileModel
{
	public $errors = [];

	public function index()
	{
		if ( $this->validateAccount() ) {
			// Load this organizations industry and all industries
			$industryRepo = $this->load( "industry-repository" );
			$this->industry = $industryRepo->get( [ "*" ], [ "id" => $this->organization->industry_id ], "single" );
	        $this->industries = $industryRepo->get( [ "*" ] );

			// Load this accounts plan
			$planRepo = $this->load( "plan-repository" );
	        $this->plan = $planRepo->get( [ "*" ], [ "id" => $this->account->plan_id ], "single" );

			// Plan details
			$planDetailsRepo = $this->load( "plan-details-repository" );
			$this->plan->details = $planDetailsRepo->get( [ "*" ], [ "plan_id" => $this->plan->id ], "single" );

			// Load this accounts payment methods
			$paymentMethodRepo = $this->load( "payment-method-repository" );
	        $this->paymentMethods = $paymentMethodRepo->get( [ "*" ], [ "account_id" => $this->account->id ] );

			$braintreePaymentMethodRepo = $this->load( "braintree-payment-method-repository" );
	        foreach ( $this->paymentMethods as $paymentMethod ) {
	            $paymentMethod->braintreePaymentMethod = $braintreePaymentMethodRepo->get(
	                $paymentMethod->braintree_payment_method_token
	            );
	        }
			$braintreeClientTokenGenerator = $this->load( "braintree-client-token-generator" );
			$this->braintreeClientToken = $braintreeClientTokenGenerator->generate(
				$this->account->braintree_customer_id
			);

			$braintreeSubscriptionRepo = $this->load( "braintree-subscription-repository" );
			$this->subscription = $braintreeSubscriptionRepo->get( $this->account->braintree_subscription_id );
		}
	}
}
