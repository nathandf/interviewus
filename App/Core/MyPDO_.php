<?php

namespace Core;

use \Conf\Config;
use \PDO;

class MyPDO extends PDO
{
	public function __construct( Config $config )
	{
		parent::__construct(
			"mysql:host={$config->configs[ "db" ][ "{$config->getEnv()}" ][ "host" ]}; dbname={$config->configs[ "db" ][ "{$config->getEnv()}" ][ "dbname" ]};",
			$config->configs[ "db" ][ "{$config->getEnv()}" ][ "user" ],
			$config->configs[ "db" ][ "{$config->getEnv()}" ][ "password" ]
		);

		$this->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );

		return $this;
	}
}
