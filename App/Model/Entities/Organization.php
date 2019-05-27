<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Organization implements EntityInterface
{
	public $id;
	public $account_id;
	public $industry_id;
	public $name;
	public $phone_id;
	public $address_id;
	public $user_id;
	public $twilio_phone_number_id;
}
