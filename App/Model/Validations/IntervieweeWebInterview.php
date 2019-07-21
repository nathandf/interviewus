<?php

namespace Model\Validations;

class IntervieweeWebInterview extends RuleSet
{
	public function __construct( $csrf_token ) {
		$this->setRuleSet([
			"token" => [
				"equals-hidden" => $csrf_token,
				"required" => true
			],
			"interviewee_answers" => [
				"required" => true,
				"is_array" => true
			]
		]);
	}
}
