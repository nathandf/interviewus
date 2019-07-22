<?php

namespace Controllers;

use \Core\Controller;

class SignUp extends Controller
{
    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );

        // Form validation
        if (
            $this->request->is( "post" ) &&
            $this->request->post( "create_account" ) != "" &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\Account( $this->request->session( "csrf-token" ) ),
                "create_account"
            )
        ) {
            return [ "SignUp:createAccount", "Signup:createAccount", null, null ];
        }

        return [ null, "SignUp:index", null, [ "error_messages" => $requestValidator->getErrors() ] ];
    }
}
