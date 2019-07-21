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
			"description" => [],
			"questions" => [
				"required" => true,
				"is_array" => true
			]
		]);
	}
}
