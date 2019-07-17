<?php

namespace Views;

use Core\AbstractView;

class ProfileView extends AbstractView
{
	protected function validateAccount()
	{
		if ( isset( $this->model->account_validated ) === false ) {
			throw new \Exception( "ProfileView '". get_class( $this ) ."' expects 'account_validated' property from Model '" . get_class( $this->model ) . "'. " );
		}

		if ( !$this->model->account_validated ) {
			$this->redirect( "sign-in" );
		}

		// Assign the data related to account validation from the model
		$this->assign( "countries", $this->model->countries );
		$this->assign( "account", $this->model->account );
		$this->assign( "organization", $this->model->organization );
		$this->assign( "user", $this->model->user );
	}
}
