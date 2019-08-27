<?php

namespace Controllers\Profile;

use \Core\Controller;

class InterviewTemplate extends Controller
{
    public function before()
    {
        if ( !isset( $this->params[ "id" ] ) ) {
            return [ null, "Error:e404", null, null ];
        }

        $userAuth = $this->load( "user-authenticator" );
        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            return [ null, "DefaultView:redirect", null, "sign-in" ];
        }

        $organizationRepo = $this->load( "organization-repository" );
        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );

        // Ensure the current interview template is owned by this organization
        $interviewTemplateRepo = $this->load( "interview-template-repository" );
        $interviewTemplate = $interviewTemplateRepo->get( [ "*" ], [ "id" => $this->params[ "id" ], "organization_id" => $this->organization->id ], "single" );

        if ( is_null( $interviewTemplate ) ) {
            return [ null, "DefaultView:redirect", null, "profile/" ];
        }
    }

    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );

        // If a form has been submitted, save the new order and value of the questions
        // and name and description of the template
        if (
            $this->request->is( "post" ) &&
            $this->request->post( "update_template" ) != "" &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\InterviewTemplateDetails(
                    $this->request->session( "csrf-token" )
                ),
                "update_template"
            )
        ) {
            return [ "InterviewTemplate:update", "DefaultView:redirect", null, "profile/interview-template/{$this->params[ "id" ]}/" ];
        }

        // Add new questions to the interivew template
        if (
            $this->request->is( "post" ) &&
            $this->request->post( "new_question" ) != "" &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\Question( $this->request->session( "csrf-token" ) ),
                "new_question"
            )
        ) {
            return [ "InterviewTemplate:addQuestion", "DefaultView:redirect", null, "profile/interview-template/{$this->params[ "id" ]}/" ];
        }

        return [ "InterviewTemplate:index", "InterviewTemplate:index", null, $requestValidator->getErrors() ];
    }
}
