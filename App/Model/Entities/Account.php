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
}
