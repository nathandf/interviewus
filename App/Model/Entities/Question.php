<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Question implements EntityInterface
{
	public $id;
	public $question_type_id;
	public $placement;
	public $body;
}