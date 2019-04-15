<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class InterviewQuestion implements EntityInterface
{
	public $id;
	public $interview_id;
	public $placement;
	public $body;
	public $dispatched;
}
