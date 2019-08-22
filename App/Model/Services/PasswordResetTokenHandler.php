<?php

namespace Model\Services;

use Conf\Config;
use Model\Entities\PasswordResetToken;

class PasswordResetTokenHandler
{
	public $passwordResetTokenRepo;
	private $baseURL;

	public function __construct(
		Config $config,
		PasswordResetTokenRepository $passwordResetTokenRepo
	) {
		$this->baseURL = $config->configs[ "email_settings" ][ $config->configs[ "environment" ] ][ "url_prefix" ] . "reset-password/";

		$this->passwordResetTokenRepo = $passwordResetTokenRepo;
	}

	public function generateResetLink( $email )
	{
		// Remove all existing tokens for this email address, valid or invalid.
		$this->removeTokens(
			$this->passwordResetTokenRepo->get( [ "*" ], [ "email" => $email ] )
		);

		// Create a reset token
		$token = $this->generatePasswordResetToken( $email );

		// Expiration in 1 hour
		$expiration = time() + 3600;

		$this->passwordResetTokenRepo->insert([
			"token" => $this->generatePasswordResetToken( $email ),
			"email" => $email,
			"expiration" => $expiration
		]);

		return $this->baseURL . $token;
	}

	private function generatePasswordResetToken( $email )
	{
		return hash( "md5", $email ) . "." . hash( "md5", base64_encode( openssl_random_pseudo_bytes( 32 ) ) );
	}

	public function validate( $passwordResetToken )
	{
		// If the token exists ...
		if ( !is_null( $passwordResetToken ) ) {
			// ... and the token is not expired
			if ( $passwordResetToken->expiration > time() ) {
				return true;
			}

			// Delete the password reset token expired
			$this->removeTokens( [ $passwordResetToken ] );
		}
		vdumpd( "not-valid" );
		return false;
	}

	private function removeTokens( array $passwordResetTokens )
	{
		foreach ( $passwordResetTokens as $token ) {
			// Delete the password reset token expired
			$this->passwordResetTokenRepo->delete(
				[ "id" ],
				[ $token->id ]
			);
		}
	}

	public function useToken( PasswordResetToken $passwordResetToken )
	{
		$this->removeTokens( [ $passwordResetToken ] );
	}
}
