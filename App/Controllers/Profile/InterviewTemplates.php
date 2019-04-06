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
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $interviewTemplateRepo = $this->load( "interview-template-repository" );
        $questionRepo = $this->load( "question-repository" );
        $positionRepo = $this->load( "position-repository" );

        $interviewTemplates = $interviewTemplateRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );
        $positions = $positionRepo->get( [ "*" ], [ "organization_id" ] );

        if (
            $input->exists() &&
            $input->issetField( "new_interview_template" ) &&
            $inputValidator->validate(
                $input,
                [
                    "name" => [],
                    "description" => [],
                    "questions" => [
                        "required" => true,
                        "is_array" => true
                    ]
                ],
                "new_interview_template"
            )
        ) {
            $interviewTemplate = $interviewTemplateRepo->insert([
                "name" => $input->get( "name" ),
                "description" => $input->get( "description" ),
                "organization_id" => $this->organization->id
            ]);

            $questions = $input->get( "questions" );

            $i = 1;
            foreach ( $questions as $question ) {
                $questionRepo->insert([
                    "interview_template_id" => $interviewTemplate->id,
                    "question_type_id" => 1,
                    "placement" => $i,
                    "body" => $question
                ]);
                $i++;
            }

            $this->view->redirect( "profile/interview-template/" . $interviewTemplate->id . "/" );
        }

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
