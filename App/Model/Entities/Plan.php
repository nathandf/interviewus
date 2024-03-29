<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Plan implements EntityInterface
{
	public $id;
	public $name;
	public $description;
	public $price;
	public $featured;
	public $braintree_plan_id;
}
