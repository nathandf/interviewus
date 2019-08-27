<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Position implements EntityInterface
{
	public $id;
	public $name;
	public $description;
	public $organization_id;
}