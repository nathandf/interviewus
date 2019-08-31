<?php

namespace Controllers;

use \Core\Controller;

class Cron extends Controller
{
	public function dispatchScheduledInterviews()
	{
		$requestValidator = $this->load( "request-validator" );
		$logger = $this->load( "my-logger" );
		
		$logger->info( "[CRON EXECUTION] [START] dispatchScheduledInterviews" );

		if (
			$this->request->is( "get" ) &&
			$requestValidator->validate(
				$this->request,
				new \Model\Validations\Cron,
				"dispatch_pending_interviews"
			)
		) {
			return [ "Interviews:dispatchScheduledInterviews", "DefaultView:index", null, null ];
		}
	}

	public function dispatchSmsInterviewQuestions()
	{
		$requestValidator = $this->load( "request-validator" );
		$logger = $this->load( "my-logger" );
		
		$logger->info( "[CRON EXECUTION] [START] dispatchSmsInterviewQuestions" );

		if (
			$this->request->is( "get" ) &&
			$requestValidator->validate(
				$this->request,
				new \Model\Validations\Cron,
				"dispatch_sms_interview_questions"
			)
		) {
			return [ "Interviews:dispatchSmsInterviewQuestions", "DefaultView:index", null, null ];
		}
	}
}
