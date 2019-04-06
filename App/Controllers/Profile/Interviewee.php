<?php

namespace Controllers\Profile;

use \Core\Controller;

class Interviewee extends Controller
{
    public function before()
    {
        $organizationRepo = $this->load( "organization-repository" );
        $intervieweeRepo = $this->load( "interviewee-repository" );

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => 0 ], "single" );

        // Ensure the current interviewee is owned by this organization
        if (
            isset( $this->params[ "id" ] ) &&
            !in_array(
                $this->params[ "id" ],
                $intervieweeRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
            )
        ) {
            $this->view->redirect( "profile/" );
        }
    }

    public function indexAction()
    {
        if ( !isset( $this->params[ "id" ] ) ) {
            $this->view->redirect( "profile/interview/new" );
        }

        $intervieweeRepo = $this->load( "interviewee-repository" );
        $interviewTemplateRepo = $this->load( "interview-template-repository" );
        $positionRepo = $this->load( "position-repository" );

        $interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $this->params[ "id" ] ], "single" );
        $interviewTemplates = $interviewTemplateRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

        $positions = $positionRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

        $this->view->assign( "interviewee", $interviewee );
        $this->view->assign( "interviewTemplates", $interviewTemplates );
        $this->view->assign( "positions", $positions );

        $this->view->setTemplate( "profile/interviewee/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }

    public function newAction()
    {

    }
}
