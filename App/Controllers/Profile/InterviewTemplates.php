<?php

namespace Controllers\Profile;

use \Core\Controller;

class InterviewTemplates extends Controller
{
    public function before()
    {
        $organizationRepo = $this->load( "organization-repository" );

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => 0 ], "single" );
    }

    public function indexAction()
    {
        $interviewTemplateRepo = $this->load( "interview-template-repository" );
        $positionRepo = $this->load( "position-repository" );

        $interviewTemplates = $interviewTemplateRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );
        $positions = $positionRepo->get( [ "*" ], [ "organization_id" ] );

        $this->view->assign( "positions", $positions );
        $this->view->assign( "interviewTemplates", $interviewTemplates );
        $this->view->assign( "csrf_token", $this->session->generateCSRFToken() );

        $this->view->setTemplate( "profile/interview-templates/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }

    public function browseAction()
    {
        $this->view->setTemplate( "profile/interview-templates/browse.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
