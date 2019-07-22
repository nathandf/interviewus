<?php

namespace Model\Models;

use Core\Model;

class Position extends ProfileModel
{
	public function index()
	{
		if ( $this->validateAccount() ) {
			// Get the position
	        $positionRepo = $this->load( "position-repository" );
	        $this->position = $positionRepo->get(
	            [ "*" ],
	            [
	                "id" => $this->request->params( "id" ),
	                "organization_id" => $this->organization->id
	            ],
	            "single"
	        );

			// Get the interviews associated with this position
			$interviewRepo = $this->load( "interview-repository" );
	        $this->interviews = $interviewRepo->get(
	            [ "*" ],
	            [
	                "position_id" => $this->position->id,
	                "organization_id" => $this->organization->id
	            ]
	        );

	        foreach ( $this->interviews as $interview ) {
				// Get the deployment type
				$deploymentTypeRepo = $this->load( "deployment-type-repository" );
	            $interview->deploymentType = $deploymentTypeRepo->get( [ "*" ], [ "id" => $interview->deployment_type_id ], "single" );

				// Get the interviewee
				$intervieweeRepo = $this->load( "interviewee-repository" );
				$interview->interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $interview->interviewee_id ], "single" );

				$interview->position = $position;

				// Get the interviews questions
				$interviewQuestionRepo = $this->load( "interview-question-repository" );
	            $interview->questions = $interviewQuestionRepo->get( [ "*" ], [ "interview_id" => $interview->id ] );

				// Get the Interviewee's answers
				$intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
	            foreach ( $interview->questions as $question ) {
	                $question->answer = $intervieweeAnswerRepo->get( [ "*" ], [ "interview_question_id" => $question->id ], "single" );
	            }
	        }
		}
	}

	public function create()
	{
		if ( $this->validateAccount() ) {
			$positionRepo = $this->load( "position-repository" );

			$this->position = $positionRepo->insert([
				"organization_id" => $this->organization->id,
				"name" => $this->request->post( "name" ),
				"description" => $this->request->post( "description" )
			]);
		}
	}

	public function update()
	{
		if ( $this->validateAccount() ) {
			$positionRepo = $this->load( "position-repository" );
			$positionRepo->update(
				[
					"name" => trim( $this->request->post( "name" ) ),
					"description" => trim( $this->request->post( "description" ) )
				],
				[ "id" => $this->request->params( "id" ) ]
			);

			$this->request->addFlashMessage( "success", "Position details updated" );
			$this->request->setFlashMessages();
		}
	}
}
