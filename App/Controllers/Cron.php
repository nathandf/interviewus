<?php

namespace Controllers;

use \Core\Controller;

class Cron extends Controller
{
	public function indexAction()
	{
		return [ null, "Default:profile", null, "" ];
	}

	public function dispatchScheduledInterviews()
	{
		$requestValidator = $this->load( "request-validator" );

		if (
			$this->request->is( "get" ) &&
			$requestValidator->validate(
				$this->request,
				[
					"cron-token" => [
						"required" => true,
						"equals" => "1234"
					]
				],
				"dispatch_pending_interviews"
			)
		) {
			return [ "Interviews:dispatchScheduledInterviews", "DefaultView:index", null, null ];
		}
	}

	public function dispatchSmsInterviewQuestions()
	{

		$requestValidator = $this->load( "request-validator" );

		if (
			$this->request->is( "get" ) &&
			$requestValidator->validate(
				$this->request,
				[
					"cron-token" => [
						"required" => true,
						"equals" => "1234"
					]
				],
				"dispatch_sms_interview_questions"
			)
		) {
			return [ "Interviews:dispatchSmsInterviewQuestions", "DefaultView:index", null, null ];
		}
	}
}
