<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Product implements EntityInterface
{
	public $id;
	public $cart_id;
	public $plan_id;
	public $billing_frequency;
}