<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class OrganizationUser implements EntityInterface
{
	public $id;
	public $organization_id;
	public $user_id;
}