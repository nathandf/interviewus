<?php

namespace Model\Validations;

use Contracts\RuleSetInterface;

abstract class RuleSet implements RuleSetInterface
{
	private $rule_set = [];

	public function setRuleSet( array $rule_set )
	{
		$this->rule_set = $rule_set;
		return $this;
	}

	public function getRuleSet()
	{
		return $this->rule_set;
	}
}
