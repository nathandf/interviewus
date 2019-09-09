<?php

namespace Model\Validations;

class SMSStatusUpdate extends RuleSet
{
	public function __construct()
	{
		$this->setRuleSet([
			"From" => [
				"required" => true
			],
			"Body" => [
				"required" => true
			]
		]);
	}
}
