<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Address implements EntityInterface
{
	public $id;
	public $address_1;
	public $address_2;
	public $city;
	public $postal_code;
	public $region;
	public $country_id;
}