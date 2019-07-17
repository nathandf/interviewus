<?php

namespace Controllers\Profile;

use \Core\Controller;

class InterviewTemplate extends Controller
{
    public function before()
    {
        if ( !isset( $this->params[ "id" ] ) ) {
            return [ null, "Profile:redirect", null, "sign-in" ];
        }

        $userAuth = $this->load( "user-authenticator" );

        $this->user = $userAuth->getAuthenticatedUser();

        if ( is_null( $this->user ) ) {
            return [ null, "Profile:redirect", null, "sign-in" ];
        }

        $accountRepo = $this->load( "account-repository" );
        $organizationRepo = $this->load( "organization-repository" );

        $this->account = $accountRepo->get( [ "*" ], [ "id" => $this->user->current_account_id ], "single" );

        $this->organization = $organizationRepo->get( [ "*" ], [ "id" => $this->user->current_organization_id ], "single" );

        // Ensure the current interview template is owned by this organization
        $interviewTemplateRepo = $this->load( "interview-template-repository" );

        $interviewTemplate = $interviewTemplateRepo->get( [ "*" ], [ "id" => $this->params[ "id" ], "organization_id" => $this->organization->id ], "single" );

        if ( is_null( $interviewTemplate ) ) {
            return [ null, "Profile:redirect", null, "profile/" ];
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
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "name" => [
                        "required" => true,
                        "max" => 128
                    ],
                    "description" => [
                        "min" => 1,
                        "max" => 256
                    ]
                ],
                "update_template"
            )
        ) {
            return [ "InterviewTemplate:update", "Profile:redirect", null, "profile/interview-template/{$this->params[ "id" ]}/" ];
        }

        // Add new questions to the interivew template
        if (
            $this->request->is( "post" ) &&
            $this->request->post( "new_question" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ],
                    "body" => [
                        "required" => true
                    ]
                ],
                "new_question"
            )
        ) {

            return [ "InterviewTemplate:addQuestion", "Profile:redirect", [ $this->params[ "id" ] ], "profile/interview-template/{$this->params[ "id" ]}/" ];
        }

        return [ "InterviewTemplate:index", "InterviewTemplate:index", null, $requestValidator->getErrors() ];
    }
}
