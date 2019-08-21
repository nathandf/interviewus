<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class PasswordResetToken implements EntityInterface
{
	public $id;
	public $token;
	public $email;
	public $expiration;
}