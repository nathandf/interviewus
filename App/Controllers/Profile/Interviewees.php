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
                new \Model\Validations\Interviewee( $this->request->session( "csrf-token" ) ),
                "new_interviewee"
            )
        ) {

            return [ "Interviewee:create", "Interviewee:create", null, null ];
        }
        
        return [ "Interviewees:index", "Interviewees:showAll", null, [ "errors" => $requestValidator->getErrors() ] ];
    }
}
