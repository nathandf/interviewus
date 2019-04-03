<?php

namespace Controllers\Profile;

use \Core\Controller;

class InterviewTemplate extends Controller
{
    public function before()
    {
        $organizationRepo = $this->load( "organization-repository" );
        $interviewTemplateRepo = $this->load( "interview-template-repository" );

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => 0 ], "single" );

        // Ensure the current interviewee is owned by this organization
        if (
            isset( $this->params[ "id" ] ) &&
            !in_array(
                $this->params[ "id" ],
                $interviewTemplateRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
            )
        ) {
            $this->view->redirect( "profile/" );
        }
    }

    public function indexAction()
    {
        if ( !isset( $this->params[ "id" ] ) ) {
            $this->view->redirect( "profile/interview-template/new" );
        }

        $interviewTemplateRepo = $this->load( "interview-template-repository" );
        $questionRepo = $this->load( "question-repository" );

        $interviewTemplate = $interviewTemplateRepo->get( [ "*" ], [ "id" => $this->params[ "id" ] ], "single" );
        $interviewTemplate->questions = $questionRepo->get( [ "*" ], [ "interview_template_id" => $interviewTemplate->id ] );

        $this->view->assign( "interviewTemplate", $interviewTemplate );

        $this->view->setTemplate( "profile/interview-template/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
