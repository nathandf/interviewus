<?php

namespace Controllers;

use \Core\Controller;

class Home extends Controller
{
    public function before()
    {

    }

    public function indexAction()
    {
        $this->view->setTemplate( "index.tpl" );
        $this->view->render( "Home.php" );
    }
}
