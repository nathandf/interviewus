<?php

namespace Views;

use Core\View;

class ResetPassword extends View
{
	public function index( $errors = [] )
	{
		$this->assign( "flash_messages", $this->request->getFlashMessages() );
		$this->setErrorMessages( $errors );

        $this->setTemplate( "reset-password/index.tpl" );
        $this->render();
	}

	public function invalidToken()
	{
		$this->setTemplate( "reset-password/invalid-token.tpl" );
        $this->render();
	}
}
