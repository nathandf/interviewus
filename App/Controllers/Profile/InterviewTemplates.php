<?php

namespace Controllers\Profile;

use \Core\Controller;

class InterviewTemplates extends Controller
{
    public function before()
    {
        $organizationRepo = $this->load( "organization-repository" );

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => 0 ], "single" );
    }

    public function indexAction()
    {
        $positionRepo = $this->load( "position-repository" );

        $positions = $positionRepo->get( [ "*" ], [ "id" => $this->organization->id ] );

        $this->view->assign( "positions", $positions );

        $this->view->setTemplate( "profile/interview-templates/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }

    public function browseAction()
    {
        $this->view->setTemplate( "profile/interview-templates/browse.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
