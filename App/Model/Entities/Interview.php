<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Interview implements EntityInterface
{
	public $id;
	public $organization_id;
	public $name;
	public $description;
	public $position_id;
	public $status;
	public $scheduled_time;
	public $start_time;
	public $end_time;
	public $token;
}