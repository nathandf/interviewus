<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class QuestionTag implements EntityInterface
{
	public $id;
	public $question_id;
	public $tag_id;
}