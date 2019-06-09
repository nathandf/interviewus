<?php

namespace Controllers\Webhooks\Twilio;

use \Core\Controller;

class Status extends Controller
{
    public function index()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $interviewQuestionRepo = $this->load( "interview-question-repository" );

        if (
            $input->exists() &&
            $inputValidator->validate(
                $input,
                [
                    "SmsSid" => [
                        "required" => true
                    ],
                    "SmsStatus" => [
                        "required" => true
                    ]
                ],
                "status"
            )
        ) {
            $interviewQuestionRepo->update(
                [ "sms_status" => $input->get( "SmsStatus" ) ],
                [ "sms_sid" => $input->get( "SmsSid" ) ]
            );
        }
    }
}
