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
        $interviewQuestionRepo = $this->load( "interview-question-repository" );
        $organizationRepo = $this->load( "organization-repository" );

        $interview = $interviewRepo->get( [ "*" ], [ "token" => $this->params[ "token" ] ], "single" );
        if ( is_null( $interview ) ) {
            $this->view->setTemplate( "i/invalid-interview.tpl" );
            $this->view->render( "App/Views/Home.php" );

            return;
        }

        $interview->questions = $interviewQuestionRepo->getAllByInterviewID( $interview->id );

        $organization = $organizationRepo->get( [ "*" ], [ "id" => $interview->organization_id ], "single" );

        $this->view->assign( "interview", $interview );
        $this->view->assign( "organization", $organization );

        $this->view->setTemplate( "i/index.tpl" );
        $this->view->render( "App/Views/Home.php" );

        return;
    }
}
