<?php

namespace Model\Validations;

class AddToCart extends RuleSet
{
	public function __construct( $csrf_token, array $plan_ids )
	{
		$this->setRuleSet([
			"token" => [
				"required" => true,
				"equals-hidden" => $csrf_token
			],
			"plan_id" => [
				"required" => true,
				"in_array" => $plan_ids
			],
			"billing_frequency" => [
				"required" => true,
				"in_array" => [ "annually", "monthly" ]
			]
		]);
	}
}
