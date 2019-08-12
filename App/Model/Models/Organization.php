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
				"account_id" => $this->account->id
			]);

			$userRepo = $this->load( "user-repository" );
			$userRepo->update(
				[ "current_organization_id" => $organization->id ],
				[ "id" => $this->user->id ]
			);
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
