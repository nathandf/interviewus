<?php

namespace Controllers;

use \Core\Controller;

class Downloads extends Controller
{
    public function interviewCSV()
    {
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "user_id" => [
                        "required" => true
                    ],
                    "account_id" => [
                        "required" => true
                    ],
                    "organization_id" => [
                        "required" => true
                    ],
                    "interview_id" => [
                        "required" => true
                    ]
                ],
                "interview_csv"
            )
        ) {
            return [ "Interview:downloadCsv", "DefaultView:index", null, null ];
        }
    }
}
