<?php

namespace Controllers\Webhooks\Twilio;

use \Core\Controller;

class Incoming extends Controller
{
    public function before()
    {
        $this->requireParam( "sid" );
    }

    public function smsAction()
    {
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                [
                    "From" => [
                        "required" => true
                    ],
                    "Body" => [
                        "required" => true
                    ]
                ],
                "recieve_sms"
            )
        ) {
            return [ "Incoming:sms", "Default:index", null, null ]
        }
    }

    public function voiceAction()
    {

    }
}
