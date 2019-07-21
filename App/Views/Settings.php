<?php

namespace Views;

class Settings extends ProfileView
{
	public function index( $errors = [] )
	{
		$this->validateAccount();

		$this->assign( "industry", $this->model->industry );
        $this->assign( "industries", $this->model->industries );
		$this->assign( "client_token", $this->model->braintreeClientToken );
		$this->assign( "subscription", $this->model->subscription );
        $this->assign( "plan", $this->model->plan );
        $this->assign( "paymentMethods", $this->model->paymentMethods );
		$this->assign( "error_messages", $errors );
		$this->assign( "flash_messages", $this->model->request->getFlashMessages() );

		$this->setTemplate( "profile/settings/index.tpl" );
        $this->render();
	}

	public function updateDefaultPaymentMethod()
	{
		$this->validateAccount();

		if ( empty( $this->model->errors ) ) {
			$this->redirect( "profile/settings" );
		}

        $this->assign( "flash_messages", $this->model->request->getFlashMessages() );
		$this->assign( "error_messages", $this->model->errors );

        $this->setTemplate( "profile/settings/index.tpl" );
        $this->render();
	}
}
