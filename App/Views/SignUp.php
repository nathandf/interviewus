<?php

namespace Views;

use Core\AbstractView;

class SignUp extends AbstractView
{
	public function index( $args )
	{
		// Form field data that was submitted
        $fields = [];

        if ( $this->model->request->post( "create_account" ) != "" ) {
            $fields[ "create_account" ][ "name" ] = $this->model->request->post( "name" );
            $fields[ "create_account" ][ "email" ] = $this->model->request->post( "email" );
            $fields[ "create_account" ][ "password" ] = $this->model->request->post( "password" );
        }

		if ( isset( $args[ "error_messages" ] ) && is_array( $args[ "error_messages" ] ) ) {
			$this->setErrorMessages( $args[ "error_messages" ] );
		}
        $this->assign( "fields", $fields );

		$this->setTemplate( "sign-up/index.tpl" );
		$this->render();
	}

	public function createAccount()
	{
		// If creating the account didn't cause any errors
		if ( empty( $this->model->errors ) ) {
			$this->redirect( "profile/" );
		} else {
			foreach ( $this->model->errors as $error ) {
				$this->addErrorMessage( "create_account", $error );
			}
		}

		// Form field data that was submitted
        $fields = [];

        if ( $this->model->request->post( "create_account" ) != "" ) {
            $fields[ "create_account" ][ "name" ] = $this->model->request->post( "name" );
            $fields[ "create_account" ][ "email" ] = $this->model->request->post( "email" );
            $fields[ "create_account" ][ "password" ] = $this->model->request->post( "password" );
        }

        $this->assign( "fields", $fields );

		$this->setTemplate( "sign-up/index.tpl" );
		$this->render();
	}

	public function createAccountAjax()
	{
		if ( empty( $this->model->errors ) ) {
			echod( json_encode( $this->model->user ) );
		}

		echod( json_encode( $this->model->errors ) );
	}

	public function createAccountAjaxError( $errors )
	{
		echod( json_encode( $errors ) );
	}
}
