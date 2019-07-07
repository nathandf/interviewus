<?php

namespace Controllers\Profile;

use \Core\Controller;

class InterviewTemplates extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $countryRepo = $this->load( "country-repository" );
        $accountRepo = $this->load( "account-repository" );
        $accountUserRepo = $this->load( "account-user-repository" );
        $organizationRepo = $this->load( "organization-repository" );

        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            $this->view->redirect( "sign-in" );
        }

        $this->account = $accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );

        $this->view->assign( "countries", $countryRepo->get( [ "*" ] ) );
        $this->view->assign( "account", $this->account );
        $this->view->assign( "organization", $this->organization );
        $this->view->assign( "user", $this->user );
    }

    public function indexAction()
    {
        $input = $this->load( "input" );
        $inputValidator = $this->load( "input-validator" );
        $interviewTemplateRepo = $this->load( "interview-template-repository" );
        $questionRepo = $this->load( "question-repository" );
        $positionRepo = $this->load( "position-repository" );

        $interviewTemplates = array_reverse( $interviewTemplateRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] ) );

        if (
            $input->exists() &&
            $input->issetField( "new_interview_template" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "name" => [
                        "required" => true
                    ],
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
                if ( !is_null( $question ) && $question != "" ) {
                    $questionRepo->insert([
                        "interview_template_id" => $interviewTemplate->id,
                        "question_type_id" => 1,
                        "placement" => $i,
                        "body" => $question
                    ]);
                }
                $i++;
            }

            $this->view->redirect( "profile/interview-template/" . $interviewTemplate->id . "/" );
        }

        if (
            $input->exists() &&
            $input->issetField( "duplicate_interview_template" ) &&
            $inputValidator->validate(
                $input,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->session->getSession( "csrf-token" )
                    ],
                    "interview_template_id" => [
                        "requried" => true,
                        "in_array" => $interviewTemplateRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
                    ]
                ],
                "duplicate_interview_template"
            )
        ) {
            $interviewTemplate = $interviewTemplateRepo->get( [ "*" ], [ "id" => $input->get( "interview_template_id" ) ], "single" );
            $interviewTemplate->questions = $questionRepo->get( [ "*" ], [ "interview_template_id" => $input->get( "interview_template_id" ) ] );

            $newInterviewTemplate = $interviewTemplateRepo->insert([
                "name" => $interviewTemplate->name . " - Copy",
                "description" => $interviewTemplate->description,
                "organization_id" => $interviewTemplate->organization_id,
                "industry_id" => $interviewTemplate->industry_id
            ]);

            foreach ( $interviewTemplate->questions as $question ) {
                $questionRepo->insert([
                    "interview_template_id" => $newInterviewTemplate->id,
                    "question_type_id" => $question->question_type_id,
                    "placement" => $question->placement,
                    "body" => $question->body
                ]);
            }

            $this->session->addFlashMessage( "Duplicated: {$newInterviewTemplate->name}" );
            $this->session->setFlashMessages();

            $this->view->redirect( "profile/interview-template/{$newInterviewTemplate->id}/" );
        }

        $this->view->assign( "interviewTemplates", $interviewTemplates );

        $this->view->setTemplate( "profile/interview-templates/index.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }

    public function browseAction()
    {
        $this->view->setTemplate( "profile/interview-templates/browse.tpl" );
        $this->view->render( "App/Views/Home.php" );
    }
}
