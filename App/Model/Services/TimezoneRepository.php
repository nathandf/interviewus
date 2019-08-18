<?php

namespace Model\Services;

class TimezoneRepository extends Repository
{
	public function getAllAscAlpha( $iso = null )
	{
		return $this->mapper->getAllAscAlpha( $iso );
	}
}
