<?php

namespace Model\Validations;

class InterviewDownload extends RuleSet
{
	public function __construct( $csrf_token ) {
		$this->setRuleSet([
			"token" => [
				"required" => true,
				"equals-hidden" => $csrf_token
			],
			"user_id" => [
				"required" => true
			],
			"account_id" => [
				"required" => true
			],
			"organization_id" => [
				"required" => true
			],
			"interview_id" => [
				"required" => true
			]
		]);
	}
}
