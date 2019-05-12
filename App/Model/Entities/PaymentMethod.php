<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class PaymentMethod implements EntityInterface
{
	public $id;
	public $account_id;
	public $braintree_payment_method_id;
	public $address_id;
	public $last_4;
	public $is_primary;
}
