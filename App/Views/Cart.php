<?php

namespace Views;

class Cart extends Page // Page inherits from View
{
	public function index( $errors = [] )
	{
		$this->showFacebookPixel( [ "InitiateCheckout" ] );
		
		$this->assign( "account", $this->model->account );
        $this->assign( "user", $this->model->user );
        $this->assign( "cart", $this->model->cart );
		$this->assign( "error_messages", $errors );
        $this->assign( "flash_messages", $this->model->request->getFlashMessages() );
        $this->assign( "client_token", $this->model->braintreeClientToken );

        $this->setTemplate( "cart/index.tpl" );
        $this->render();
	}

	public function purchase()
	{
		if ( empty( $this->model->errors ) ) {
			$this->redirect( "profile/" );
		}

		foreach ( $this->model->errors as $error ) {
			$this->request->addFlashMessage( "error", $error );
		}

		$this->model->request->setFlashMessages();

		$this->redirect( "cart/" );
	}
}
