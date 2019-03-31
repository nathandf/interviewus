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
        $this->view->render( "App/Views/Home.php" );
    }

    public function signInAction()
    {
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );

        $this->view->setTemplate( "sign-in.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }

    public function privacyPolicyAction()
    {

    }

    public function termsAndConditionsAction()
    {

    }
}
