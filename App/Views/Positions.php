<?php

namespace Views;

class Positions extends ProfileView
{
	public function index( $errors = [] )
	{
		$this->validateAccount();

		$this->assign( "positions", array_reverse( $this->model->positions ) );
		$this->assign( "error_messages", $errors );

        $this->setTemplate( "profile/positions/index.tpl" );
        $this->render();
	}
}
