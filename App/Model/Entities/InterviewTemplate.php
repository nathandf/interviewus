<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class InterviewTemplate implements EntityInterface
{
	public $id;
	public $name;
	public $description;
	public $organization_id;
	public $industry_id;
}
