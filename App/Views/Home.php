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

	public function signInAjax( $args = null )
	{
		if ( !is_null( $args ) ) {
			echod( json_encode( $args ) );
		}
	}

	public function authenticateUserAjax()
	{
		if ( !is_null( $this->model->user ) ) {
			echod( json_encode( $this->model->user ) );
		}

		echod( json_encode( [ "errors" => "Invalid Credentials" ]) );
	}

	public function authenticateUser()
	{
		if ( $this->model->user_authenticated ) {
			$this->redirect( "profile/" );
		}

		$this->addErrorMessage( "sign_in", "User authentication failed" );
		$this->setTemplate( "sign-in.tpl" );
	}

	public function termsAndConditions()
	{
		echo( "terms and conditions" );
	}

	public function privacyPolicy()
	{
		echo( "privacy policy" );
	}
}
