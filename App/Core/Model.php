<?php

namespace Core;

/**
 * Class Model
 * @package Core
 */
abstract class Model extends CoreObject
{
	public $request;
	public $container;

	public function __contstruct( Request $request, Container $container )
	{
		$this->request = $request;
		$this->setContainer( $container );
	}
}
