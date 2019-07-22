<?php

namespace Controllers\Profile;

use \Core\Controller;

class Positions extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            return [ null, "DefaultView:redirect", null, "sign-in" ];
        }
    }

    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "new_position" ) != "" &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\NamesDescription( $this->request->session( "csrf-token" ) ),
                "new_position"
            )
        ) {
            return [ "Position:create", "Position:create", null, null ];
        }

        return [ "Positions:index", "Positions:index", null, $requestValidator->getErrors() ];
    }
}
