<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Conversation implements EntityInterface
{
	public $id;
	public $twilio_phone_number_id;
	public $e164_phone_number;
}
