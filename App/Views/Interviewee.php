<?php

namespace Views;

use Core\AbstractView;

class Interviewee extends AbstractView
{
	public function create()
	{
		$this->redirect( "profile/interviewee/{$this->model->interviewee->id}/" );
	}
}
