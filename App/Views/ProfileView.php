<?php

namespace Views;

use Core\AbstractView;
use Core\Model;
use Core\DIContainer;

class ProfileView extends AbstractView
{
	public function __construct( Model $model, DIContainer $container )
	{
		parent::__construct( $model, $container );

		// Require that all Models passed as arguments extend class ProfileModel
		if (
            !is_null( $this->model ) &&
            !is_a( $model, "Model\Models\ProfileModel" )
        ) {
            throw new \Exception( "ProfileView expects model of class ProfileModel as an argument." );
        }
	}

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
