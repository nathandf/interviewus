<?php

namespace Contracts;

interface RuleSetInterface
{
	public function setRules( array $rules );
	public function getRules();
}
