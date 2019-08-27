<?php

namespace Core;

use \Conf\Config;
use \Katzgrau\KLogger\Logger;

class MyLogger extends Logger
{
	public function __construct( Config $config )
	{
		parent::__construct( $config->configs[ "logs_directory" ] );

		return $this;
	}
}
