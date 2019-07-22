<?php

namespace Views;

use Core\View;

class InterviewTemplate extends ProfileView
{
	public function index( $errors = [] )
	{
		$this->validateAccount();
		
		$this->assign( "interviewTemplate", $this->model->interviewTemplate );
		$this->assign( "flash_messages", $this->model->request->getFlashMessages() );
		$this->assign( "error_message", $errors );
		$this->setTemplate( "profile/interview-template/index.tpl" );
		$this->render();
	}

	public function create()
	{
		$this->redirect( "profile/interview-template/{$this->model->interviewTemplate->id}/" );
	}
}
