<?php

namespace Views;

class ResetPassword extends Page // Page inherits from View
{
	public function index( $errors = [] )
	{	
		$this->showFacebookPixel();
		
		$this->assign( "flash_messages", $this->request->getFlashMessages() );
		$this->setErrorMessages( $errors );

        $this->setTemplate( "reset-password/index.tpl" );
        $this->render();
	}

	public function invalidToken()
	{
		$this->showFacebookPixel();
		
		$this->setTemplate( "reset-password/invalid-token.tpl" );
        $this->render();
	}
}
