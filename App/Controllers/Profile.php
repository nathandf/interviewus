<?php

namespace Controllers;

use \Core\Controller;

class Profile extends Controller
{
    public function before()
    {
        // TODO Set a real organization
        $this->organization_id = null;
    }

    public function indexAction()
    {
        $interviewRepo = $this->load( "interview-repository" );

        $interviews = $interviewRepo->get( [ "*" ], [ "organization_id" => $this->organization_id ] );

        $this->view->assign( "interviews", $interviews );

        $this->view->setTemplate( "profile/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
