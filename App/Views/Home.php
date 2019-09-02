<?php

namespace Views;

class Home extends Page // Page inherits from View
{
	public function index()
	{
		$this->showFacebookPixel();
		$this->pageTitle( "Automated SMS Interviews | Interview By Text Message" );
		
		$this->setTemplate( "index.tpl" );
		$this->render();
	}

	public function resetPassword()
	{
		$this->showFacebookPixel();
		
		$this->assign( "flash_messages", $this->request->getFlashMessages() );

		$this->setTemplate( "reset-password.tpl" );
		$this->render();
	}

	public function signIn( array $args )
	{
		$this->showFacebookPixel();
		
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
		$this->showFacebookPixel();
		
		if ( $this->model->user_authenticated ) {
			$this->redirect( "profile/" );
		}

		$this->addErrorMessage( "sign_in", "User authentication failed" );
		$this->setTemplate( "sign-in.tpl" );
		$this->render();
	}

	public function termsAndConditions()
	{
		$this->showFacebookPixel();
		echo( "terms and conditions" );
	}

	public function privacyPolicy()
	{
		$this->showFacebookPixel();
		echo( "privacy policy" );
	}
}
