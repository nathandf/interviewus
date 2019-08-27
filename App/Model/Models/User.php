<?php

namespace Model\Models;

class User extends ProfileModel
{
	public $errors = [];
	public function logout()
	{
		$userAuth = $this->load( "user-authenticator" );
        $userAuth->logout();
	}

	public function changeOrganization()
	{
		if ( $this->validateAccount() ) {
			$userRepo = $this->load( "user-repository" );

			$userRepo->update(
				[ "current_organization_id" => $this->request->post( "organization_id" ) ],
				[ "id" => $this->user->id ]
			);
		}
	}
}
