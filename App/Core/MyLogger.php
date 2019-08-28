<?php

namespace Core;

use \Conf\Config;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class MyLogger extends Logger
{
	private $logfile = "main.log";

	public function __construct( Config $config )
	{
		parent::__construct( "main" );

		$this->pushHandler(
			new StreamHandler(
				$config->configs[ "logs_directory" ] . $this->logfile
			)
		);

		return $this;
	}
}
