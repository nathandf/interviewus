<?php

namespace Model\Models;

use Core\Model;

class InterviewQuestion extends Model
{
	public function updateSmsStatus()
	{
		$interviewQuestionRepo = $this->load( "interview-question-repository" );

		$interviewQuestionRepo->update(
			[ "sms_status" => $this->request->post( "SmsStatus" ) ],
			[ "sms_sid" => $this->request->post( "SmsSid" ) ]
		);
	}
}
