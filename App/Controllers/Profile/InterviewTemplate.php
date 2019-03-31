<?php

namespace Controllers\Profile;

use \Core\Controller;

class InterviewTemplate extends Controller
{
    public function before()
    {

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

    }
}
