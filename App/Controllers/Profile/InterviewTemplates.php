<?php

namespace Controllers\Profile;

use \Core\Controller;

class InterviewTemplates extends Controller
{
    public function before()
    {
        $userAuth = $this->load( "user-authenticator" );
        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            return [ null, "DefaultView:redirect", null, "sign-in" ];
        }

        $organizationRepo = $this->load( "organization-repository" );
        $this->organization = $organizationRepo->get(
            [ "*" ],
            [ "id" => $this->user->current_organization_id ],
            "single"
        );
    }

    public function indexAction()
    {
        $requestValidator = $this->load( "request-validator" );
        $interviewTemplateRepo = $this->load( "interview-template-repository" );

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "new_interview_template" ) != "" &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\InterviewTemplate( $this->request->session( "csrf-token" ) ),
                "new_interview_template"
            )
        ) {
            return [ "InterviewTemplate:create", "InterviewTemplate:create", null, null ];
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "duplicate_interview_template" ) != "" &&
            $requestValidator->validate(
                $this->request,
                new \Model\Validations\InterviewTemplateDuplication(
                    $this->request->session( "csrf-token" ),
                    $interviewTemplateRepo->get( [ "id" ], [ "organization_id" => $this->organization->id ], "raw" )
                ),
                "duplicate_interview_template"
            )
        ) {
            return [ "InterviewTemplates:duplicate", "InterviewTemplates:duplicate", null, null ];
        }

        return [ "InterviewTemplates:index", "InterviewTemplates:index", null, $requestValidator->getErrors() ];
    }

    public function browseAction()
    {
        return [ "InterviewTemplates:browse", "InterviewTemplates:browse", null, null ];
    }
}
