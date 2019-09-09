<?php

namespace Model\Validations;

class SmsStatusUpdate extends RuleSet
{
	public function __construct()
	{
		$this->setRuleSet([
			"SmsStatus" => [
				"required" => true
			],
			"SmsSid" => [
				"required" => true
			]
		]);
	}
}
