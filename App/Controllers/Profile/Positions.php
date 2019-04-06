<?php

namespace Controllers\Profile;

use \Core\Controller;

class Positions extends Controller
{
    public function before()
    {
        $organizationRepo = $this->load( "organization-repository" );

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => 0 ], "single" );
    }

    public function indexAction()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $positionRepo = $this->load( "position-repository" );

        $positions = $positionRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

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
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );

        $this->view->setTemplate( "profile/positions/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
