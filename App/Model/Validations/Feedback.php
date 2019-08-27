<?php

namespace Model\Validations;

class Feedback extends RuleSet
{
	public function __construct( $csrf_token ) {
		$this->setRuleSet([
			"token" => [
				"required" => true,
				"equals-hidden" => $csrf_token
			],
			"user" => [
				"required" => true
			],
			"account" => [
				"required" => true
			],
			"opinion" => [
				"required" => true
			],
			"subject" => [
				"required" => true
			],
			"message" => [
				"required" => true
			],
			"recommendation" => [
				"required" => true
			]
		]);
	}
}
