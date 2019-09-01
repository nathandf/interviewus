<?php

namespace Controllers;

use \Core\Controller;

class Cron extends Controller
{
	public function dispatchScheduledInterviews()
	{
		$logger = $this->load( "my-logger" );
		$config = $this->load( "config" );
		
		if ( in_array( $this->request->ip(), $this->config->configs[ "approved_ip_addresses" ] ) ) {
			$logger->info( "[CRON] [START] DISPATCH SCHEDULED INTERVIEW" );
			return [ "Interviews:dispatchScheduledInterviews", "DefaultView:index", null, null ];
		}
		
		$logger->info( "[CRON] [UNAPPROVED IP] {$this->request->ip()}" );
	}

	public function dispatchSmsInterviewQuestions()
	{
		$logger = $this->load( "my-logger" );
		$config = $this->load( "config" );
		
		if ( in_array( $this->request->ip(), $this->config->configs[ "approved_ip_addresses" ] ) ) {
			$logger->info( "[CRON] [START] DISPATCH INTERVIEW QUESTIONS" );
			return [ "Interviews:dispatchSmsInterviewQuestions", "DefaultView:index", null, null ];
		}
		
		$logger->info( "[CRON] [UNAPPROVED IP] {$this->request->ip()}" );
	}
}
