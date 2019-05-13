<?php

namespace Model\Entities;

use Contracts\EntityInterface;
use Model\DomainObjects\Person;

class Interviewee extends Person implements EntityInterface
{
	public $id;
	public $first_name;
	public $last_name;
	public $email;
	public $phone_id;
	public $address_id;
	public $image_id;
}
