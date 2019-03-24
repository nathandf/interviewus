<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class File implements EntityInterface
{
	public $id;
	public $filename;
	public $file_type;
}