<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Interview implements EntityInterface
{
	public $id;
	public $organization_id;
	public $interviewee_id;
	public $interview_template_id;
	public $position_id;
	public $status;
	public $scheduled_time;
	public $start_time;
	public $end_time;
	public $token;
}
