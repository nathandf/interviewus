<?php

namespace Model\Validations;

class Cron extends RuleSet
{
	public function __construct() {
		$this->setRuleSet([
			"cron-token" => [
				"required" => true,
				"equals" => "1234"
			]
		]);
	}
}
