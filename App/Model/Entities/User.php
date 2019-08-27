<?php

namespace Model\Entities;

use Contracts\EntityInterface;
use Model\DomainObjects\Person;

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
	public $token;
	public $image_id;
	public $current_account_id;
	public $current_organization_id;
}
