<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Image implements EntityInterface
{
	public $id;
	public $filename;
	public $file_type;
}