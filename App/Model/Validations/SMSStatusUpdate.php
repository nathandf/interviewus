<?php

namespace Model\Validations;

class SMSStatusUpdate extends RuleSet
{
	public function __construct( $csrf_token, array $plan_ids )
	{
		$this->setRuleSet([
			"From" => [
				"required" => true
			],
			"Body" => [
				"required" => true
			]
		]);
	}
}
