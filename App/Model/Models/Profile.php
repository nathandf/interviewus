<?php

namespace Model\Models;

class Profile extends ProfileModel
{
	public function logout()
	{
		$userAuth = $this->load( "user-authenticator" );
        $userAuth->logout();
	}

	public function index()
	{
		if ( $this->validateAccount() ) {
			$interviewRepo = $this->load( "interview-repository" );
	        $interviewQuestionRepo = $this->load( "interview-question-repository" );
	        $intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
	        $interviewTemplateRepo = $this->load( "interview-template-repository" );
	        $intervieweeRepo = $this->load( "interviewee-repository" );
	        $phoneRepo = $this->load( "phone-repository" );
	        $positionRepo = $this->load( "position-repository" );
	        $questionRepo = $this->load( "question-repository" );
	        $deploymentTypeRepo = $this->load( "deployment-type-repository" );

			$this->interviews = $interviewRepo->get(
                [ "*" ],
                [
                    "organization_id" => $this->organization->id,
                    "mode" => "visible"
                ]
            );

			foreach ( $this->interviews as $interview ) {
	            $interview->deploymentType = $deploymentTypeRepo->get( [ "*" ], [ "id" => $interview->deployment_type_id ], "single" );
	            $interview->interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $interview->interviewee_id ], "single" );
	            $interview->position = $positionRepo->get( [ "*" ], [ "id" => $interview->position_id ], "single" );
	            $interview->questions = $interviewQuestionRepo->get( [ "*" ], [ "interview_id" => $interview->id ] );

	            foreach ( $interview->questions as $question ) {
	                $question->answer = $intervieweeAnswerRepo->get( [ "*" ], [ "interview_question_id" => $question->id ], "single" );
	            }
	        }

	        $this->interviewTemplates = $interviewTemplateRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

	        $this->positions = $positionRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );

	        $this->interviewees = $intervieweeRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );
		}
	}
}
