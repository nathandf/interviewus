<?php

namespace Model\Models;

use Core\Model;

class Positions extends ProfileModel
{
	public function index()
	{
		if ( $this->validateAccount() ) {
			$positionRepo = $this->load( "position-repository" );
	        $this->positions = $positionRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );
		}
	}
}
