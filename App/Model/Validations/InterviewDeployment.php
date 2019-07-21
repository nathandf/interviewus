<?php

namespace Model\Validations;

class InterviewDeployment extends RuleSet
{
	public function __construct(
		$csrf_token,
		array $interviewee_ids,
		array $position_ids,
		array $interview_template_ids
	) {
		$this->setRuleSet([
			"token" => [
				"equals-hidden" => $csrf_token,
				"required" => true
			],
			"deployment_type_id" => [
				"required" => true,
				"in_array" => [ 1, 2 ]
			],
			"interviewee_id" => [
				"required" => true,
				"in_array" => $interviewee_ids
			],
			"position_id" => [
				"in_array" => $position_ids
			],
			"interview_template_id" => [
				"required" => true,
				"in_array" => $interview_template_ids
			],
			"schedule_type" => [
				"required" => true,
				"in_array" => [ 1, 2 ]
			],
			"date" => [],
			"Hour" => [],
			"Minute" => [],
			"Meridian" => []
		]);
	}
}
