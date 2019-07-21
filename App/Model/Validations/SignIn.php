<?php

namespace Model\Validations;

class SignIn extends RuleSet
{
	public function __construct( $csrf_token ) {
		$this->setRuleSet([
			"token" => [
				"required" => true,
				"equals-hidden" => $csrf_token
			],
			"email" => [
				"required" => true,
				"email" => true
			],
			"password" => [
				"required" => true
			],
		]);
	}
}
