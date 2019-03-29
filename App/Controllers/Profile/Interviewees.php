<?php

namespace Controllers\Profile;

use \Core\Controller;

class Interviewees extends Controller
{
    public function before()
    {
        
    }

    public function indexAction()
    {
        $this->view->setTemplate( "profile/interviewees/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
