<?php

namespace Model\Models;

use Core\Model;

class InterviewTemplate extends Model
{
	public function updateSmsStatus()
	{
		$interviewQuestionRepo = $this->load( "interview-question-repository" );

		$interviewQuestionRepo->update(
			[ "sms_status" => $this->model->request->post( "SmsStatus" ) ],
			[ "sms_sid" => $this->model->request->post( "SmsSid" ) ]
		);

		$logger = $this->load( "logger" );
		$logger->info( "Validated" );
	}
}
