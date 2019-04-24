<?php

namespace Controllers;

use \Core\Controller;

class Pricing extends Controller
{
    public function before()
    {
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );
    }

    public function indexAction()
    {
        $planRepo = $this->load( "plan-repository" );
        $planDetailsRepo = $this->load( "plan-details-repository" );

        $plans = $planRepo->get( [ "*" ] );

        foreach ( $plans as $plan ) {
            $plan->details = $planDetailsRepo->get( [ "*" ], [ "plan_id" => $plan->id ], "single" );
        }

        $this->view->assign( "plans", $plans );
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );

        $this->view->setTemplate( "pricing/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
