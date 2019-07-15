<?php

namespace Views;

class Interviewees extends ProfileView
{
	public function showAll( $errors = null )
	{
		$this->validateAccount();

		$this->assign( "countries", $this->model->countries );
		$this->assign( "account", $this->model->account );
		$this->assign( "organization", $this->model->organization );
		$this->assign( "user", $this->model->user );
		$this->assign( "interviewees", $this->model->interviewees );

		if ( !is_null( $errors ) ) {
			$this->assign( "error_messages", $errors );
		}

		$this->setTemplate( "profile/interviewees/index.tpl" );
		$this->render();
	}
}
