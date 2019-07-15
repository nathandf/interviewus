<?php

namespace Model\Models;

class Organization extends ProfileModel
{
	public function create()
	{
		parent::validateAccount();

		$organizationRepo = $this->load( "organization-repository" );

		$this->newOrganization = $organizationRepo->insert([
			"name" => $this->request->post( "name" ),
			"industry_id" => $this->request->post( "industry_id" )
		]);
	}

	public function update()
	{
		parent::validateAccount();

		$organizationRepo = $this->load( "organization-repository" );
		$organizationRepo->update(
			[
				"industry_id" => $this->request->post( "industry_id" ),
				"name" => $this->request->post( "organization" )
			],
			[ "id" => $this->organization->id ]
		);

		$this->request->addFlashMessage( "Organization updated" );
		$this->request->setFlashMessages();
	}
}