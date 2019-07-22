<?php

namespace Model\Validations;

class BraintreeWebhookNotification extends RuleSet
{
	public function __construct( $csrf_token ) {
		$this->setRuleSet([
			"bt_signature" => [
				"required" => true
			],
			"bt_payload" => [
				"required" => true
			]
		]);
	}
}
