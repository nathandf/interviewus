<?php

namespace Core;

use \Conf\Config;

class MyLogger
{
	public $logger;

	public function __construct( Config $config )
	{
		$this->logger = new \Katzgrau\KLogger\Logger(
			$config->configs[ "logs_directory" ]
		);

		return $this;
	}

	public function info( $message )
	{
		$this->logger->info( $message );
	}

	public function debug( $message )
	{
		$this->logger->debug( $message );
	}

	public function warning( $message )
	{
		$this->logger->warning( $message );
	}

	public function error( $message )
	{
		$this->logger->error( $message );
	}
}
