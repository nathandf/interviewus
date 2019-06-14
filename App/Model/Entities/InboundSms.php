<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class InboundSms implements EntityInterface
{
	public $id;
	public $conversation_id;
	public $body;
	public $recieved_at;
	public $concatenated_sms_id;
	public $twilio_sms_sid;
}