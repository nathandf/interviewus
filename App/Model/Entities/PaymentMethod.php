<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class PaymentMethod implements EntityInterface
{
	public $id;
	public $account_id;
	public $braintree_payment_method_token;
	public $is_default;
}
