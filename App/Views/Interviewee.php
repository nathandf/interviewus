<?php

namespace Views;

class Interviewee extends ProfileView
{
	public function create()
	{
		$this->redirect( "profile/interviewee/{$this->model->interviewee->id}/" );
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

		$this->redirect( "profile/interviewee/{$this->model->interviewee->id}/" );
	}
}
