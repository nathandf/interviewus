<?php

namespace Model\Models;

use Core\Model;

class Profile extends Model
{
	public function logout()
	{
		$userAuth = $this->load( "user-authenticator" );
        $userAuth->logout();
	}
}
