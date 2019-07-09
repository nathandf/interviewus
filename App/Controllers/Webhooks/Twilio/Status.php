<?php

namespace Controllers\Webhooks\Twilio;

use \Core\Controller;

class Status extends Controller
{
    public function index()
    {
        
        $requestValidator = $this->load( "request-validator" );
        $interviewQuestionRepo = $this->load( "interview-question-repository" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
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
                [ "sms_status" => $this->request->post( "SmsStatus" ) ],
                [ "sms_sid" => $this->request->post( "SmsSid" ) ]
            );
        }
    }
}
