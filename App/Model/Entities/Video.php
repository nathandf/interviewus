<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Video implements EntityInterface
{
	public $id;
	public $filename;
	public $file_type;
}