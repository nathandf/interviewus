<?php

namespace Views;

class Interviewee extends ProfileView
{
	public function index( $errors )
	{
		$this->validateAccount();


		$timezoneRepo = $this->load( "timezone-repository" );
		$this->assign( "timezones", $timezoneRepo->getAllAscAlpha( "US" ) );

		$this->model->interviewee->interviews = array_reverse( $this->model->interviewee->interviews );

		// Add a context indicator and reference to the interviewee on each interview
		foreach ( $this->model->interviewee->interviews as $interview ) {
			$interview->context = "interviewee";
			$interview->interviewee = &$this->model->interviewee;
		}

		$this->assign( "interviewee", $this->model->interviewee );

		$this->assign( "selected_interviewee", $this->model->interviewee );

		$this->assign( "interviewTemplates", $this->model->interviewTemplates );
        $this->assign( "positions", $this->model->positions );
        $this->setErrorMessages( $errors );
        $this->assign( "flash_messages", $this->request->getFlashMessages() );

		$this->setTemplate( "profile/interviewee/index.tpl" );
		$this->render();
	}

	public function create()
	{
		$this->redirect( "profile/interviewee/{$this->model->interviewee->id}/" );
	}

	public function update( $redirect_url )
	{
		$this->redirect( $redirect_url );
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
