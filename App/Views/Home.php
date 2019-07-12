<?php

namespace Views;

use Core\AbstractView;

class Home extends AbstractView
{
	public function index()
	{
		$this->setTemplate( "index.tpl" );
	}

	public function signIn( array $args )
	{
		$this->setErrorMessages( $args[ "error_messages" ] );
		$this->setTemplate( "sign-in.tpl" );
	}

	public function loginRedirect()
	{
		$this->redirect( "profile/" );
	}

	public function authenticateUser()
	{
		if ( $this->model->user_authenticated ) {
			$this->redirect( "profile/" );
		}
		
		$this->addErrorMessage( "sign_in", "User authentication failed" );
		$this->setTemplate( "sign-in.tpl" );
	}
}
