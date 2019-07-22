<?php

namespace Views;

use Core\View;

class Home extends View
{
	public function index()
	{
		$this->setTemplate( "index.tpl" );
		$this->render();
	}

	public function signIn( array $args )
	{
		$this->setErrorMessages( $args[ "error_messages" ] );
		$this->setTemplate( "sign-in.tpl" );
		$this->render();
	}

	public function signInAjax( $args = null )
	{
		if ( !is_null( $args ) ) {
			$this->respondWithJson( $args );
		}
	}

	public function authenticateUserAjax()
	{
		if ( !is_null( $this->model->user ) ) {
			$this->respondWithJson( $this->model->user );
		}

		$this->respondWithJson( [ "errors" => "Invalid Credentials" ] );
	}

	public function authenticateUser()
	{
		if ( $this->model->user_authenticated ) {
			$this->redirect( "profile/" );
		}

		$this->addErrorMessage( "sign_in", "User authentication failed" );
		$this->setTemplate( "sign-in.tpl" );
		$this->render();
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
