<?php

namespace Controllers\Webhooks\Twilio;

use \Core\Controller;

class Status extends Controller
{
    public function index()
    {
        $logger = $this->load( "logger" );
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\SmsStatusUpdate,
                "status"
            )
        ) {
            return [ "InterviewQuestion:updateSmsStatus", "Default:index", null, null ];
        }

        $logger->info( "SmsStatus Update Failed" );
    }
}
