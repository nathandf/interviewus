<?php

namespace Views;

use Core\AbstractView;

class Position extends AbstractView
{
	public function create()
	{
		$this->redirect( "profile/position/{$this->model->position->id}/" );
	}
}
