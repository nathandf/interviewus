<?php

namespace Model\Services\TwilioAPI;

use Twilio\Rest\Client;

class ClientInitializer
{
	public $configs;
	public $server_environment;
	public $status_callback;

	public function __construct( \Conf\Config $Config )
	{
		// Get facebook configs
		$this->setConfigs( $Config->configs[ "packages" ][ "twilio" ] );

		// Set server environment
		$this->setServerEnv( $Config->getEnv() );

		switch ( $this->server_environment ) {
			case "development":
				$this->setStatusCallback( $this->configs[ "development" ][ "status_callback" ] );
				break;
			case "production":
				$this->setStatusCallback( $this->configs[ "production" ][ "status_callback" ] );
				break;
			default:
				$this->setStatusCallback( $this->configs[ "production" ][ "status_callback" ] );
				break;
		}
	}

	public function init()
	{
		$client = new Client(
			$this->configs[ "account_sid" ],
			$this->configs[ "auth_token" ]
		);

		return $client;
	}

	private function setConfigs( array $configs )
	{
		$this->configs = $configs;
	}

	public function getConfigs()
	{
		return $this->configs;
	}

	private function setServerEnv( $server_environment )
	{
		$this->server_environment = $server_environment;
	}

	public function getServerEnv()
	{
		return $this->server_environment;
	}

	private function setStatusCallback( $status_callback )
	{
		$this->status_callback = $status_callback;
	}

	public function getStatusCallback()
	{
		return $this->status_callback;
	}
}
