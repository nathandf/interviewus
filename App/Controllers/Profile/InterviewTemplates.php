<?php

namespace Controllers\Profile;

use \Core\Controller;

class InterviewTemplates extends Controller
{
    public function before()
    {

    }

    public function indexAction()
    {
        $this->view->setTemplate( "profile/interview-templates/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }

    public function newAction()
    {

    }

    public function browseAction()
    {
        $this->view->setTemplate( "profile/interview-templates/browse.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
