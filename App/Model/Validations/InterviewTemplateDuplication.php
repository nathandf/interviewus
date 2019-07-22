<?php

namespace Model\Validations;

class InterviewTemplateDuplication extends RuleSet
{
	public function __construct( $csrf_token, array $interview_template_ids )
	{
		$this->setRuleSet([
			"token" => [
				"required" => true,
				"equals-hidden" => $csrf_token
			],
			"interview_template_id" => [
				"requried" => true,
				"in_array" => $interview_template_ids
			]
		]);
	}
}
