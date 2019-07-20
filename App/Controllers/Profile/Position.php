<?php

namespace Controllers\Profile;

use \Core\Controller;

class Position extends Controller
{
    public function before()
    {
        if ( !isset( $this->params[ "id" ] ) ) {
            return [ null, "Error:e404", null, null ];
        }
        
        $positionRepo = $this->load( "position-repository" );

        $userAuth = $this->load( "user-authenticator" );
        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            return [ null, "DefaultView:redirect", null, "sign-in" ];
        }

        $position = $positionRepo->get(
            [ "*" ],
            [
                "id" => $this->params[ "id" ],
                "organization_id" => $this->user->current_organization_id
            ],
            "single"
        );

        // Ensure the current position is owned by this organization
        if ( is_null( $position ) ) {
            return [ null, "DefaultView:redirect", null, "profile/" ];
        }
    }

    public function indexAction()
    {
        if ( !isset( $this->params[ "id" ] ) ) {
            return [ null, "DefaultView:redirect", null, "profile/" ];
        }

        $requestValidator = $this->load( "request-validator" );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "update_position" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "name" => [
                        "required" => true,
                        "max" => 128
                    ],
                    "description" => [
                        "max" => 512
                    ]
                ],
                "update_position"
            )
        ) {
            return [ "Position:update", "DefaultView:redirect", null, "profile/position/{$this->params[ "id" ]}/" ];
        }

        return [ "Position:index", "Position:index", null, $requestValidator->getErrors() ];
    }
}
