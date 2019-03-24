<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class User extends Person implements EntityInterface
{
	public $id;
	public $role;
	public $first_name;
	public $last_name;
	public $email;
	public $phone_id;
	public $address_id;
	public $password;
	public $image_id;
}
