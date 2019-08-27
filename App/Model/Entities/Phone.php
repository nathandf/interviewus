<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Phone implements EntityInterface
{
	public $id;
	public $country_code;
	public $national_number;
	public $e164_phone_number;

	public function getNiceNumber()
	{
		return "+" . $this->country_code . " " . $this->national_number;
	}
}
