<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Account implements EntityInterface
{
	public $id;
	public $account_type_id;
	public $sms_interviews;
	public $web_interviews;
	public $users;
	public $plan_id;
	public $recurs_on;
	public $status;
	public $braintree_customer_id;
	public $braintree_subscription_id;
	public $timezone;

	public function validateInterviewCredit( DeploymentType $deploymentType, $debits = 1 ) {
		if (
			( $this->{$deploymentType->name . "_interviews"} - $debits ) >= 0 ||
			$this->{$deploymentType->name . "_interviews"} == -1 // -1 means unlimited
		) {
			return true;
		}

		return false;
	}
}
