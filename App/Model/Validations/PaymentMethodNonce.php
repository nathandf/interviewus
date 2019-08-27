<?php

namespace Model\Validations;

class PaymentMethodNonce extends RuleSet
{
	public function __construct( $csrf_token )
	{
		$this->setRuleSet([
			"token" => [
				"equals-hidden" => $csrf_token
			],
			"payment_method_nonce" => [
				"required" => true
			]
		]);
	}
}
