<?php

namespace Views;

use Core\AbstractView;

class Pricing extends AbstractView
{
	public function index( $args )
	{
		if ( isset( $args[ "errors" ] ) ) {
			$this->assign( "error_messages", $args[ "errors" ] );
		}

		$this->assign( "plans", $this->model->plans );
		$this->assign( "countries", $this->model->countries );
        $this->assign( "account", $this->model->account );
        $this->assign( "organization", $this->model->organization );
        $this->assign( "user", $this->model->user );

		$this->setTemplate( "pricing/index.tpl" );
		$this->render();
	}
}
