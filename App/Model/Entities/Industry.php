<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Industry implements EntityInterface
{
	public $id;
	public $name;
	public $display_name;
}