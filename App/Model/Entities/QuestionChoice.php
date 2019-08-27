<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class QuestionChoice implements EntityInterface
{
	public $id;
	public $question_id;
	public $placement;
	public $body;
}