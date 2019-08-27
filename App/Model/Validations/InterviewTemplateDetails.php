<?php

namespace Model\Validations;

class InterviewTemplateDetails extends RuleSet
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
			"description" => []
		]);
	}
}
