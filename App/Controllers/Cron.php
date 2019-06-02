<?php

namespace Controllers;

use \Core\Controller;

class Cron extends Controller
{
	public function indexAction()
	{
		$this->view->redirect( "" );
	}

	public function cullTwilioNumbers()
	{
		// Destroy twilio numbers that are about renew but have no conversations
	}
}
