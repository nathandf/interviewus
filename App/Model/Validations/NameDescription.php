<?php

namespace Model\Validations;

class NameDescription extends RuleSet
{
	public function __construct( $csrf_token )
	{
		$this->setRuleSet([
			"token" => [
				"required" => true,
				"equals-hidden" => $csrf_token
			],
			"name" => [
				"required" => true,
				"max" => 128
			],
			"description" => [
				"max" => 512
			]
		]);
	}
}
