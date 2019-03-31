<?php

namespace Controllers\Profile;

use \Core\Controller;

class Positions extends Controller
{
    public function before()
    {

    }

    public function indexAction()
    {
        $this->view->setTemplate( "profile/positions/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
