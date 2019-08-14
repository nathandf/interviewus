<?php

namespace Views;

class Profile extends ProfileView
{
	public function logout()
	{
		$this->redirect( "" );
	}

	public function deployInterview()
	{
		$this->validateAccount();

		if ( !empty( $this->model->errors ) ) {
			foreach ( $this->model->errors as $error ) {
				$this->model->request->addFlashMessage( "error", $error );
			}

			$this->model->request->setFlashMessages();
		}

		$this->redirect( "profile/" );
	}

	public function showAll( $errors = [] )
	{
		$this->validateAccount();

		$timezoneRepo = $this->load( "timezone-repository" );
		$this->assign( "timezones", $timezoneRepo->getAllAscAlpha( "US" ) );

		$this->assign( "interviews", array_reverse( $this->model->interviews ) );
        $this->setErrorMessages( $errors );
        $this->assign( "flash_messages", $this->model->request->getFlashMessages() );

		$this->setTemplate( "profile/index.tpl" );
		$this->render();
	}
}
