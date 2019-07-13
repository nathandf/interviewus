<?php

namespace Model\Models;

use Core\Model;

class Home extends Model
{
	public $user;
	public $user_authenticated = false;

	public function index()
	{
		
	}

	public function authenticateUser()
	{
		$userAuth = $this->load( "user-authenticator" );

		$this->user_authenticated = $userAuth->authenticate(
			$this->request->post( "email" ),
			$this->request->post( "password" )
		);
	}
}
