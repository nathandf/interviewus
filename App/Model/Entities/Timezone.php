<?php

namespace Model\Entities;

use Contracts\EntityInterface;

class Timezone implements EntityInterface
{
	public $id;
	public $country_code;
	public $timezone;
	public $gmt_offset;
	public $dst_offset;
	public $raw_offset;
}