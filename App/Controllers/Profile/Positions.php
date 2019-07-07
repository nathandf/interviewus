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
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $positionRepo = $this->load( "position-repository" );

        $positions = array_reverse( $positionRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] ) );

        if (
            $input->exists() &&
            $input->issetField( "new_position" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
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
            $position = $positionRepo->insert([
                "organization_id" => $this->organization->id,
                "name" => $input->get( "name" ),
                "description" => $input->get( "description" )
            ]);

            $this->view->redirect( "profile/position/" . $position->id . "/" );
        }

        $this->view->assign( "positions", $positions );
        $this->view->assign( "error_messages", $inputValidator->getErrors() );

        $this->view->setTemplate( "profile/positions/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
