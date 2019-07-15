<?php

namespace Controllers\Profile;

use \Core\Controller;

class Positions extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $countryRepo = $this->load( "country-repository" );
        $accountRepo = $this->load( "account-repository" );
        $accountUserRepo = $this->load( "account-user-repository" );
        $organizationRepo = $this->load( "organization-repository" );

        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            $this->view->redirect( "sign-in" );
        }

        $this->account = $accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );

        $this->view->assign( "countries", $countryRepo->get( [ "*" ] ) );
        $this->view->assign( "account", $this->account );
        $this->view->assign( "organization", $this->organization );
        $this->view->assign( "user", $this->user );
    }

    public function indexAction()
    {

        $requestValidator = $this->load( "request-validator" );
        $positionRepo = $this->load( "position-repository" );

        $positions = array_reverse( $positionRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] ) );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "new_position" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "name" => [
                        "required" => true,
                    ],
                    "description" => [
                        "max" => 256
                    ]
                ],
                "new_position"
            )
        ) {

            return [ "Position:create", "Position:create", null, null ];
        }

        $this->view->assign( "positions", $positions );
        $this->view->assign( "error_messages", $requestValidator->getErrors() );

        $this->view->setTemplate( "profile/positions/index.tpl" );
        $this->view->render( "App/Views/Index.php" );
    }
}
