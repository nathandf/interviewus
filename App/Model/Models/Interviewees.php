<?php

namespace Model\Models;

class Interviewees extends ProfileModel
{
	public function index()
	{
		if ( $this->validateAccount() ) {
			$intervieweeRepo = $this->load( "interviewee-repository" );
			$phoneRepo = $this->load( "phone-repository" );

			$this->interviewees = $intervieweeRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

			foreach ( $this->interviewees as $interviewee ) {
				$interviewee->phone = $phoneRepo->get( [ "*" ], [ "id" => $interviewee->phone_id ], "single" );
			}

			return;
		}

		return;
	}
}
