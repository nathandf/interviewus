<?php

namespace Views;

use Core\AbstractView;

class DefaultView extends AbstractView
{
	public function index()
	{
		header( "HTTP/1.1 200 OK" );
	}
}
