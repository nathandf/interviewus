<?php

namespace Model\Validations;

class Interviewee extends RuleSet
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
			"country_code" => [
				"required" => true
			],
			"national_number" => [
				"required" => true,
				"phone" => true
			]
		]);
	}
}
