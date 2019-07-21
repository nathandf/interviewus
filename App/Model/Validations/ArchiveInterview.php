<?php

namespace Model\Validations;

class ArchiveInterview extends RuleSet
{
	public function __construct( $csrf_token, array $interview_ids ) {
		$this->setRuleSet([
			"token" => [
				"required" => true,
				"equals-hidden" => $csrf_token
			],
			"interview_id" => [
				"required" => true,
				"in_array" => $interview_ids
			]
		]);
	}
}
