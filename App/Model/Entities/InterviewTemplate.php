<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class InterviewTemplate implements EntityInterface
{
	public $id;
	public $organization_id;
	public $industry_id;
}