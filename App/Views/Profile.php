<?php

namespace Views;

use Core\AbstractView;

class Profile extends AbstractView
{
	public function logout()
	{
		$this->redirect( "" );
	}
}
