<?php

namespace Controllers\Webhooks\Twilio;

use \Core\Controller;

class Status extends Controller
{
    public function index()
    {
        $requestValidator = $this->load( "request-validator" );

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
            return [ "InterviewQuestion:updateSmsStatus", "Default:index", null, null ];
        }
    }
}
