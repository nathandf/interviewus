<?php

namespace Controllers;

use \Core\Controller;

class Profile extends Controller
{
    public function before()
    {

    }

    public function indexAction()
    {
        $this->view->setTemplate( "profile/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
