<?php

namespace Model\Validations;

class CsrfOnly extends RuleSet
{
	public function __construct( $csrf_token ) {
		$this->setRuleSet([
			"token" => [
				"required" => true,
				"equals-hidden" => $csrf_token
			]
		]);
	}
}
