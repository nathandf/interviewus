<?php

namespace Controllers;

use \Core\Controller;

class I extends Controller
{
    public function before()
    {
        $this->requireParam( "token" );
    }

    public function indexAction()
    {
        $interviewRepo = $this->load( "interview-repository" );

        $interview = $interviewRepo->get( [ "*" ], [ "token" => $this->params[ "token" ] ], "single" );

        if ( is_null( $interview ) ) {
            $this->view->setTemplate( "i/invalid-interview.tpl" );
            $this->view->render( "App/Views/Home.php" );

            return;
        }

        $this->view->setTemplate( "i/index.tpl" );
        $this->view->render( "App/Views/Home.php" );

        return;
    }
}
