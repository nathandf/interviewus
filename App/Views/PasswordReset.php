<?php

namespace Views;

use Core\View;

class PasswordReset extends View
{
	public function index()
	{
		$this->assign( "flash_messages", $this->request->getFlashMessages() );

        $this->setTemplate( "password-reset/index.tpl" );
        $this->render();
	}
}
