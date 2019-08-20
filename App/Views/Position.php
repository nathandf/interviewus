<?php

namespace Views;

class Position extends ProfileView
{
	public function index()
	{
		$this->validateAccount();

		// Add a context
		foreach ( $this->model->interviews as $interview ) {
			$interview->context = "position";
		}

		$this->assign( "interviews", array_reverse( $this->model->interviews ) );
        $this->assign( "position", $this->model->position );
        $this->assign( "flash_messages", $this->model->request->getFlashMessages() );

        $this->setTemplate( "profile/position/index.tpl" );
        $this->render();
	}

	public function create()
	{
		$this->redirect( "profile/position/{$this->model->position->id}/" );
	}
}
