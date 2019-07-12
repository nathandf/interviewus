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

	public function __construct( Request $request, DIContainer $container )
	{
		$this->request = $request;
		$this->setContainer( $container );
	}
}
