<?php

namespace Controllers;

use \Core\Controller;

class Cron extends Controller
{
	public function dispatchScheduledInterviews()
	{
		$requestValidator = $this->load( "request-validator" );
		$logger = $this->load( "my-logger" );
		
		$start = time();
		
		$logger->info( "[CRON] [START] DISPATCH SCHEDULED INTERVIEWS" );

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
		
		$logger->info( "[CRON] VALIDATION ERROR" );
	}

	public function dispatchSmsInterviewQuestions()
	{
		$requestValidator = $this->load( "request-validator" );
		$logger = $this->load( "my-logger" );
		
		$start = time();
		
		$logger->info( "[CRON] [START] DISPATCH INTERVIEW QUESTIONS" );

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
		
		$logger->info( "[CRON] VALIDATION ERROR" );
	}
}
