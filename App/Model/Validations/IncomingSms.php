<?php

namespace Model\Validations;

class IncomingSms extends RuleSet
{
	public function __construct() {
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
