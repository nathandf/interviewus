<?php

namespace Views;

class Sms extends \Core\View
{
	public function send()
	{
		$this->setTemplate( "sendsms.tpl" );
		$this->render();
	}
}
