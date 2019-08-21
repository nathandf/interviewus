<?php

namespace Model\Services;

use Conf\Config;

class PasswordResetTokenHandler
{
	public $passwordResetTokenRepository;
	private $baseURL;

	public function __construct(
		Config $config,
		PasswordResetTokenRepository $passwordResetTokenRepository
	) {
		$this->baseURL = $config->configs[ "email_settings" ][ $config->configs[ "environment" ] ][ "url_prefix" ] . "password-reset/";

		$this->passwordResetTokenRepository = $passwordResetTokenRepository;
	}

	public function generateResetLink( $email )
	{
		// Create a reset token
		$token = $this->generatePasswordResetToken( $email );

		// Expiration in 1 hour
		$expiration = time() + 3600;

		$this->passwordResetTokenRepository->insert([
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
}
