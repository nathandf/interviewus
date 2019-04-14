<?php

namespace Controllers\Profile;

use \Core\Controller;

class Position extends Controller
{
    public function before()
    {
        $organizationRepo = $this->load( "organization-repository" );
        $positionRepo = $this->load( "position-repository" );

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => 0 ], "single" );

        // Ensure the current position is owned by this organization
        if (
            isset( $this->params[ "id" ] ) &&
            !in_array(
                $this->params[ "id" ],
                $positionRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
            )
        ) {
            $this->view->redirect( "profile/" );
        }
    }

    public function indexAction()
    {
        if ( !isset( $this->params[ "id" ] ) ) {
            $this->view->redirect( "profile/" );
        }

        $positionRepo = $this->load( "position-repository" );

        $position = $positionRepo->get( [ "*" ], [ "id" => $this->params[ "id" ] ], "single" );

        $this->view->assign( "position", $position );

        $this->view->setTemplate( "profile/position/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
