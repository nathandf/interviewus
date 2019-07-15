<?php

namespace Controllers\Profile;

use \Core\Controller;

class Interviewees extends Controller
{
    public function index()
    {
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "new_interviewee" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "name" => [
                        "required" => true
                    ],
                    "email" => [
                        "required" => true,
                        "email" => true
                    ],
                    "country_code" => [
                        "required" => true
                    ],
                    "national_number" => [
                        "required" => true,
                        "phone" => true
                    ]
                ],
                "new_interviewee"
                )
        ) {

            return [ "Interviewee:create", "Interviewee:create", null, null ];
        }

        return [ "Interviewees:index", "Interviewees:showAll", null, [ "errors" => $requestValidator->getErrors() ] ];
    }
}
