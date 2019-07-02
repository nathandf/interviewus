<?php

namespace Controllers;

use \Core\Controller;

class Downloads extends Controller
{
    public function interviewCSV()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $csv = $this->load( "csv-generator" );

        if (
            $input->exists() &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
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

        }
    }
}
