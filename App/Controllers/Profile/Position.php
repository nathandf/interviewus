<?php

namespace Controllers\Profile;

use \Core\Controller;

class Position extends Controller
{
    public function before()
    {

    }

    public function indexAction()
    {
        if ( !isset( $this->params[ "id" ] ) ) {
            $this->view->redirect( "profile/position/new" );
        }

        $this->view->setTemplate( "profile/position/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }

    public function newAction()
    {
        $this->view->setTemplate( "profile/position/new.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
