<?php

namespace Views;

use Core\AbstractView;

class I extends AbstractView
{
	public function deploymentSuccessful()
	{
		$this->setTemplate( "i/sms-interview-deployment-success.tpl" );
	}

	public function interviewComplete()
	{
		$this->setTemplate( "i/interview-complete.tpl" );
	}
}
