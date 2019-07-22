<?php

namespace Views;

use Core\View;

class DefaultView extends View
{
	public function index()
	{
		header( "HTTP/1.1 200 OK" );
	}
}
