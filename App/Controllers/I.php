<?php

namespace Controllers;

use \Core\Controller;

class I extends Controller
{
    public function before()
    {
        if ( $this->issetParam( "token" ) === false ) {
            return [ null, "Error:e404", null, null ];
        }
    }

    public function indexAction()
    {
        $interviewRepo = $this->load( "interview-repository" );
        $interview = $interviewRepo->get( [ "*" ], [ "token" => $this->params[ "token" ] ], "single" );

		// Redirect to error page if interview is invalid
        if ( is_null( $interview ) ) {
            return [ null, "I:invalid", null, null ];
        }

		// Load the organization that owns this interview
		$organizationRepo = $this->load( "organization-repository" );
		$organization = $organizationRepo->get( [ "*" ], [ "id" => $interview->organization_id ], "single" );

        // Redirect to interview is complete, redirect to the interview complete page
        if ( $interview->status == "complete" ) {
            return [ null, "DefaultView:redirect", null, "i/{$this->params[ "token" ]}/interview-complete" ];
        }

        // Redirect to interview deployment success screen if the interview status
        // is active and interview is of the SMS deployment type
        if (
            $interview->deployment_type_id == 1 &&
            $interview->status == "active"
        ) {
            return [ null, "DefaultView:redirect", null, "i/{$this->params[ "token" ]}/deployment-successful" ];
        }

        $requestValidator = $this->load( "request-validator" );

        // Dispatch the interview
        if (
            $this->request->is( "post" ) &&
            $this->request->post( "start_interview" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "required" => true,
                        "equals-hidden" => $this->request->session( "csrf-token" )
                    ]
                ],
                "start_interview"
            )
        ) {
            return [ "I:start", "I:start", null, null ];
        }

        if (
            $this->request->is( "post" ) &&
            $this->request->post( "web_interview" ) != "" &&
            $requestValidator->validate(
                $this->request,
                [
                    "token" => [
                        "equals-hidden" => $this->request->session( "csrf-token" ),
                        "required" => true
                    ],
                    "interviewee_answers" => [
                        "required" => true,
                        "is_array" => true
                    ]
                ],
                "web_interview"
            )
        ) {

            return [ "I:webInterview", "DefaultView:redirect", null, "i/{$this->params[ "token" ]}/" ];
        }

        return [ "I:index", "I:index", null, $requestValidator->getErrors() ];
    }

    public function deploymentSuccessfulAction()
    {
        return [ "I:deploymentSuccessful", "I:deploymentSuccessful", null, null ];
    }

    public function interviewCompleteAction()
    {
        return [ null, "I:interviewComplete", null, null ];
    }
}
