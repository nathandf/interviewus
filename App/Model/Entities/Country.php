<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Country implements EntityInterface
{
	public $id;
	public $iso;
	public $nicename;
	public $iso3;
	public $numcode;
	public $phone_code;
}