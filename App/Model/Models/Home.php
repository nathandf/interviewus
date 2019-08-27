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

		$this->user =  $userAuth->getAuthenticatedUser();
	}

	public function sendResetLink()
	{
		// Get the user with the email address provided.
		$email = strtolower( trim( $this->request->post( "email" ) ) );
		$userRepo = $this->load( "user-repository" );
		$user = $userRepo->get( [ "*" ], [ "email" => $email ], "single" );

		// If a user doesn't exist with this email, don't send the password reset email
		if ( !is_null( $user ) ) {
			// Create and send user email a password reset link
			$mailer = $this->load( "mailer" );
			$emailBuilder = $this->load( "email-builder" );
			$domainObjectFactory = $this->load( "domain-object-factory" );

			$passwordResetTokenHandler = $this->load( "password-reset-token-handler" );
			$emailContext = $domainObjectFactory->build( "EmailContext" );

			$emailContext->addProps([
				"password_reset_url" => $passwordResetTokenHandler->generateResetLink( $user->email )
			]);

			$resp = $mailer->setTo( $user->email, $user->getFullName() )
				->setFrom( "noreply@interviewus.net", "InterviewUs" )
				->setSubject( "Password Reset Request" )
				->setContent( $emailBuilder->build( "password-reset-request.html", $emailContext ) )
				->mail();
		}

		$this->request->addFlashMessage( "success", "Reset link sent" );
		$this->request->setFlashMessages();
	}
}
