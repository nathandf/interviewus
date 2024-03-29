<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class ConcatenatedSms implements EntityInterface
{
	public $id;
	public $conversation_id;
	public $body;
	public $created_at;
	public $update_at;
}
