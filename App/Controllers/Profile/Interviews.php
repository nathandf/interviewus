<?php

namespace Controllers\Profile;

use \Core\Controller;

class Interviews extends Controller
{
    public function before()
    {
        
    }

    public function indexAction()
    {
        $this->view->setTemplate( "profile/interviews/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
