<?php

namespace Views;

class Interviewees extends ProfileView
{
	public function showAll( $errors = null )
	{
		$this->validateAccount();

		if ( !is_null( $errors ) ) {
			$this->assign( "error_messages", $errors );
		}

		$this->assign( "interviewees", array_reverse( $this->model->interviewees ) );

		$this->setTemplate( "profile/interviewees/index.tpl" );
		$this->render();
	}
}
