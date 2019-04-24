<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class PlanDetails implements EntityInterface
{
	public $id;
	public $plan_id;
	public $sms_interviews;
	public $web_interviews;
	public $users;
}
