<?php

namespace Model\Validations;

class Account extends RuleSet
{
	public function __construct( $csrf_token )
	{
		$this->setRuleSet([
			"token" => [
				"required" => true,
				"equals-hidden" => $csrf_token
			],
			"name" => [
				"required" => true
			],
			"email" => [
				"required" => true,
				"email" => true
			],
			"password" => [
				"required" => true,
				"min" => 6
			]
		]);
	}
}
