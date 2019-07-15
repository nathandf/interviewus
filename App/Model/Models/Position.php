<?php

namespace Model\Models;

use Core\Model;

class Position extends ProfileModel
{
	public function create()
	{
		if ( parent::validateAccount() ) {
			$positionRepo = $this->load( "position-repository" );

			$this->position = $positionRepo->insert([
				"organization_id" => $this->organization->id,
				"name" => $this->request->post( "name" ),
				"description" => $this->request->post( "description" )
			]);
		}
	}
}
