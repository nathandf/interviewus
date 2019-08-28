<?php

namespace Model\Models;

class Interviewees extends ProfileModel
{
	public function index()
	{
		if ( $this->validateAccount() ) {
			$intervieweeRepo = $this->load( "interviewee-repository" );
	        $this->interviewees = $intervieweeRepo->get(
				[ "*" ],
				[ "organization_id" => $this->organization->id ]
			);

			$phoneRepo = $this->load( "phone-repository" );
			$imageRepo = $this->load( "image-repository" );

			foreach ( $this->interviewees as $interviewee ) {

				// Get phones owned by interviewee
				$interviewee->phone = null;
				if ( !is_null( $interviewee->phone_id ) ) {
					$interviewee->phone = $phoneRepo->get(
						[ "*" ],
						[ "id" => $interviewee->phone_id ],
						"single"
					);
				}

				// Get images owned by interviewee
				$interviewee->image = null;
				if ( !is_null( $interviewee->image_id ) ) {
					$interviewee->image = $imageRepo->get(
						[ "*" ],
						[ "id" => $interviewee->image_id ],
						"single"
					);
				}
			}
		}
	}
}
