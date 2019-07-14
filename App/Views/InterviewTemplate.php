<?php

namespace Views;

use Core\AbstractView;

class InterviewTemplate extends AbstractView
{
	public function create()
	{
		$this->redirect( "profile/interview-template/{$this->model->interviewTemplate->id}/" );
	}
}
