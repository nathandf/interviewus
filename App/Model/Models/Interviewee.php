<?php

namespace Model\Models;

class Interviewee extends ProfileModel
{
	public function create()
	{
		if ( parent::validateAccount() ) {
			$phoneRepo = $this->load( "phone-repository" );
			$intervieweeRepo = $this->load( "interviewee-repository" );

			$phone = $phoneRepo->insert([
				"country_code" => $this->request->post( "country_code" ),
				"national_number" => $this->request->post( "national_number" ),
				"e164_phone_number" => "+" . $this->request->post( "country_code" ) . $this->request->post( "national_number" )
			]);

			$this->interviewee = $intervieweeRepo->insert([
				"organization_id" => $this->organization->id,
				"first_name" => $this->request->post( "name" ),
				"email" => $this->request->post( "email" ),
				"phone_id" => $phone->id
			]);

			// Update the first and last name
			$this->interviewee->setNames( $this->interviewee->first_name );

			if (
				!is_null( $this->interviewee->getFirstName() ) &&
				!is_null( $this->interviewee->getLastName() )
			) {
				$intervieweeRepo->update(
					[
						"first_name" => $this->interviewee->getFirstName(),
						"last_name" => $this->interviewee->getLastName()
					],
					[
						"id" => $this->interviewee->id
					]
				);
			}
		}
	}
}
