<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Interview implements EntityInterface
{
	public $id;
	public $deployment_type_id;
	public $organization_id;
	public $conversation_id;
	public $interviewee_id;
	public $interview_template_id;
	public $position_id;
	public $status;
	public $last_interview_question_id;
	public $scheduled_time;
	public $start_time;
	public $end_time;
	public $token;
}
