<?php

namespace Model\Validations;

class PersonPhoneEmail extends RuleSet
{
	public function __construct( $csrf_token )
	{
		$this->setRuleSet([
			"token" => [
				"required" => true,
				"equals-hidden" => $csrf_token
			],
			"first_name" => [
				"required" => true,
				"max" => 128
			],
			"last_name" => [
				"max" => 128
			],
			"email" => [
				"required" => true,
				"email" => true
			],
			"country_code" => [
				"required" => true,
				"number" => true
			],
			"national_number" => [
				"required" => true
			]
		]);
	}
}
