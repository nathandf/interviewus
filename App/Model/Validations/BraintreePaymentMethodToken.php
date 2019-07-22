<?php

namespace Model\Validations;

class BraintreePaymentMethodToken extends RuleSet
{
	public function __construct( $csrf_token, array $payment_method_tokens )
	{
		$this->setRuleSet([
			"token" => [
				"equals-hidden" => $csrf_token
			],
			"braintree_payment_method_token" => [
				"required" => true,
				"in_array" => $payment_method_tokens
			]
		]);
	}
}
