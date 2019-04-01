<?php

namespace Controllers\Profile;

use \Core\Controller;

class InterviewTemplate extends Controller
{
    public function before()
    {
        $organizationRepo = $this->load( "organization-repository" );

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => 0 ], "single" );
    }

    public function indexAction()
    {
        if ( !isset( $this->params[ "id" ] ) ) {
            $this->view->redirect( "profile/interview-template/new" );
        }

        $this->view->setTemplate( "profile/interview-template/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }

    public function newAction()
    {
        $positionRepo = $this->load( "position-repository" );

        $positions = $positionRepo->get( [ "*" ], [ "id" => $this->organization->id ] );

        $this->view->assign( "positions", $positions );

        $this->view->setTemplate( "profile/interview-template/new.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
