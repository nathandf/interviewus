<?php

namespace Model\Validations;

class Organization extends RuleSet
{
	public function __construct( $csrf_token, array $industry_ids )
	{
		$this->setRuleSet([
			"token" => [
				"equals-hidden" => $csrf_token,
				"required" => true
			],
			"organization" => [
				"min" => 1
			],
			"industry_id" => [
				"in_array" => $industry_ids
			]
		]);
	}
}
