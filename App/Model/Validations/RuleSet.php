<?php

namespace Model\Validations;

use Contracts\RuleSetInterface;

abstract class RuleSet implements RuleSetInterface
{
	private $rules = [];

	public function setRules( array $rules )
	{
		$this->rules = $rules;
		return $this;
	}

	public function getRules()
	{
		return $this->rules;
	}
}
