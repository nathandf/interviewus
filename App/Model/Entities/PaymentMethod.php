<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class PaymentMethod implements EntityInterface
{
	public $id;
	public $braintree_payment_method_id;
	public $is_default;
}