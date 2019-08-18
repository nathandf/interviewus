<?php

namespace Model\Models;

class Organization extends ProfileModel
{
	public function create()
	{
		if ( $this->validateAccount() ) {
			$organizationRepo = $this->load( "organization-repository" );
			$organization = $organizationRepo->insert([
				"name" => $this->request->post( "name" ),
				"account_id" => $this->account->id,
				"user_id" => $this->organization->user_id,
				"timezone" => $this->request->post( "timezone" )
			]);

			$userRepo = $this->load( "user-repository" );
			$userRepo->update(
				[ "current_organization_id" => $organization->id ],
				[ "id" => $this->user->id ]
			);

			// Create new OrganizationUsers for this organization
			$organizationUserRepo = $this->load( "organization-user-repository" );
			$organizationUser = $organizationUserRepo->insert([
				"organization_id" => $organization->id,
				"user_id" => $this->user->id
			]);

			return $organization;
		}
	}

	public function createAndDuplicate()
	{
		// Create the new interview template
		$newOrganization = $this->create();

		$duplications = $this->request->post( "duplications" );

		foreach ( $duplications as $duplication ) {

			switch ( $duplication ) {

				case "templates":
					// Duplicate the interview template and the questions of the
					// current organiztation for the newOrganization create in the
					// create method;
					$interviewTemplateRepo = $this->load( "interview-template-repository" );

					// Get all the interview templates for the current organization
					$interviewTemplates = $interviewTemplateRepo->get(
						[ "*" ],
						[ "organization_id" => $this->organization->id ]
					);

					// For each interview template of the current organization,
					// create a new interview template with the same name and
					// description
					foreach ( $interviewTemplates as $template ) {
						$newInterviewTemplate = $interviewTemplateRepo->insert([
							"name" => $template->name,
							"description" => $template->description,
							"organization_id" => $newOrganization->id
						]);

						// Get all of the questions for the original interview template
						$questionRepo = $this->load( "question-repository" );
						$questions = $questionRepo->get(
							[ "*" ],
							[ "interview_template_id" => $template->id ]
						);

						// Duplicate the current questions
						foreach ( $questions as $question ) {
							$newQuestion = $questionRepo->insert([
								"interview_template_id" => $newInterviewTemplate->id,
								"question_type_id" => $question->question_type_id,
								"placement" => $question->placement,
								"body" => $question->body
							]);
						}
					}

					break;

				case "positions":
					// Duplicate all of the positions of the current organization
					$positionRepo = $this->load( "position-repository" );

					// Get all existing positions of the current organization
					$positions = $positionRepo->get(
						[ "*" ],
						[ "organization_id" => $this->organization->id ]
					);

					// Create a duplicate position for each position of the current
					// organization
					foreach ( $positions as $position ) {
						$newPositions = $positionRepo->insert([
							"name" => $position->name,
							"description" => $position->description,
							"organization_id" => $newOrganization->id
						]);
					}

					break;

				case "interviewees":

					$intervieweeRepo = $this->load( "interviewee-repository" );

					// Get all existing interviewees of the current organization
					$interviewees = $intervieweeRepo->get(
						[ "*" ],
						[ "organization_id" => $this->organization->id ]
					);

					// Create a duplicate interviewee for each interviewee of the
					// current organization
					$phoneRepo = $this->load( "phone-repository" );
					$addressRepo = $this->load( "address-repository" );

					foreach ( $interviewees as $interviewee ) {

						$new_phone_id = null;
						$new_address_id = null;

						// Duplicate the current phone if phone id is not null
						if ( !is_null( $interviewee->phone_id ) ) {
							// Get the interviewees existing phone
							$phone = $phoneRepo->get( [ "*" ], [ "id" => $interviewee->phone_id ], "single" );

							// Create the new phone from the existing phone details
							$newPhone = $phoneRepo->insert([
								"country_code" => $phone->country_code,
								"national_number" => $phone->national_number
							]);

							$new_phone_id = $newPhone->id;
						}

						// Duplicate the current address if address id is not null
						if ( !is_null( $interviewee->address_id ) ) {

							// Get the interviewees existing address
							$address = $addressRepo->get( [ "*" ], [ "id" => $interviewe->address_id ], "single" );

							// Create the new address from the existing address
							$newAddress = $addressRepo->insert([
								"address_1" => $address->address_1,
								"address_2" => $address->address_2,
								"city" => $address->city,
								"postal_code" => $address->postal_code,
								"region" => $address->region,
								"address_1" => $address->address_1,
								"country_id" => $address->country_id
							]);

							$new_address_id = $newAddress->id;
						}

						$newInterviewee = $intervieweeRepo->insert([
							"organization_id" => $newOrganization->id,
							"first_name" => $interviewee->first_name,
							"last_name" => $interviewee->last_name,
							"email" => $interviewee->email,
							"phone_id" => $new_phone_id,
							"address_id" => $new_address_id,
							"image_id" => $interviewee->image_id // TODO Duplicate image file and image entity
						]);
					}

					break;
			}
		}
	}

	public function update()
	{
		if ( $this->validateAccount() ) {
			$organizationRepo = $this->load( "organization-repository" );
			$organizationRepo->update(
				[
					"name" => $this->request->post( "name" )
				],
				[ "id" => $this->organization->id ]
			);

			$this->request->addFlashMessage( "success", "Worksapce updated" );
			$this->request->setFlashMessages();
		}
	}
}
