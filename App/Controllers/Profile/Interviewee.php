<?php

namespace Controllers\Profile;

use \Core\Controller;

class Interviewee extends Controller
{
    public function before()
    {
        
    }

    public function indexAction()
    {
        if ( !isset( $this->params[ "id" ] ) ) {
            $this->view->redirect( "profile/interview/new" );
        }

        $this->view->setTemplate( "profile/interviewee/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }

    public function newAction()
    {

    }
}
