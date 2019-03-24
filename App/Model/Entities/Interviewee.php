<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Interviewee implements EntityInterface
{
	public $id;
	public $first_name;
	public $last_name;
	public $email;
	public $phone_id;
	public $address_id;
	public $image_id;
}