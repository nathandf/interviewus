<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class TwilioPhoneNumber implements EntityInterface
{
	public $id;
	public $sid;
	public $phone_number;
	public $friendly_number;
}
