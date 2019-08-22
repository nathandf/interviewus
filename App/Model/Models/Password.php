<?php

namespace Model\Models;

use Core\Model;

class Password extends Model
{
	public function reset()
	{
		// Get the password reset token with the specified token string
		$passwordResetTokenRepo = $this->load( "password-reset-token-repository" );
        $passwordResetToken = $passwordResetTokenRepo->get( [ "*" ], [ "token" => $this->request->params( "token" ) ], "single" );

		// Validate the token
        $passwordResetTokenHandler = $this->load( "password-reset-token-handler" );

        if ( $passwordResetTokenHandler->validate( $passwordResetToken ) ) {

			// Update the users password where users email == the email provided by the token
			$userRepo = $this->load( "user-repository" );
			$userRepo->update(
				[ "password" => password_hash( trim( $this->request->post( "password" ) ), PASSWORD_BCRYPT ) ],
				[ "email" => $passwordResetToken->email ]
			);

			// Destroy the token so it cannot be reused
			$passwordResetTokenHandler->useToken( $passwordResetToken );

			// Logout the current user
			$userAuth = $this->load( "user-authenticator" );
			$userAuth->logOut();
		}
	}
}
