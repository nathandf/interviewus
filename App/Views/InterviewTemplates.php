<?php

namespace Views;

use Core\AbstractView;

class InterviewTemplates extends ProfileView
{
	public function index( $errors = [] )
	{
		$this->validateAccount();

		$this->assign( "interviewTemplates", array_reverse( $this->model->interviewTemplates ) );
		$this->assign( "errors", $errors );

		$this->setTemplate( "profile/interview-templates/index.tpl" );
        $this->render();
	}

	public function browse()
	{
		$this->validateAccount();

		$this->setTemplate( "profile/interview-templates/browse.tpl" );
        $this->render();
	}

	public function duplicate()
	{
		$this->redirect( "profile/interview-template/{$this->model->newInterviewTemplate->id}/" );
	}
}
