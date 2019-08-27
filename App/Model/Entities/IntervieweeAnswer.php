<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class IntervieweeAnswer implements EntityInterface
{
	public $id;
	public $interview_question_id;
	public $body;
}