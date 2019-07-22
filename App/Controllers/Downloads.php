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
                new \Model\Validations\InterviewDownload( $this->request->session( "csrf-token" ) ),
                "interview_csv"
            )
        ) {
            return [ "Interview:downloadCsv", "DefaultView:index", null, null ];
        }
    }
}
