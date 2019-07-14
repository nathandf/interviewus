<?php

namespace Views;

use Core\AbstractView;

class Error extends AbstractView
{
	public function e302()
	{
		$this->setTemplate( "302.shtml" );
		$this->reneder();
	}

	public function e404()
	{
		$this->setTemplate( "404.shtml" );
		$this->render();
	}

	public function e500()
	{
		$this->setTemplate( "500.shtml" );
		$this->render();
	}
}
