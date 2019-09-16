<?php

namespace Core;

use \Conf\Config;
use \PDO;

class MyPdo extends PDO
{
	public function __construct( Config $config )
	{
		try {
			parent::__construct(
				"mysql:host={$config->configs[ "database" ][ "host" ]}; dbname={$config->configs[ "database" ][ "dbname" ]};",
				$config->configs[ "database" ][ "user" ],
				$config->configs[ "database" ][ "password" ]
			);

			$this->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
		} catch (\Exception $e) {
			die( "Database connection error" );
		}

		return $this;
	}
}
