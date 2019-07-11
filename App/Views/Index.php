<?php

namespace Views;

use Core\AbstractView;

class Index extends AbstractView
{
	public function index()
	{
		$this->setTemplate( "index.tpl" );
	}
}
