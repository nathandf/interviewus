<?php

namespace Model\Models;

class InterviewTemplate extends ProfileModel
{
	public function index()
	{
		if ( $this->validateAccount() ) {
			$interviewTemplateRepo = $this->load( "interview-template-repository" );
			$this->interviewTemplate = $interviewTemplateRepo->get(
				[ "*" ],
				[ "id" => $this->request->params( "id" ), "organization_id" => $this->organization->id ],
				"single"
			);
			$questionRepo = $this->load( "question-repository" );
			$this->interviewTemplate->questions = $questionRepo->getAllByInterviewTemplateID( $this->request->params( "id" ) );
		}
	}

	public function create()
	{
		if ( $this->validateAccount() ) {
			$interviewTemplateRepo = $this->load( "interview-template-repository" );
			$questionRepo = $this->load( "question-repository" );

			$this->interviewTemplate = $interviewTemplateRepo->insert([
				"name" => $this->request->post( "name" ),
				"description" => $this->request->post( "description" ),
				"organization_id" => $this->organization->id
			]);

			$questions = $this->request->post( "questions" );

			$i = 1;
			foreach ( $questions as $question ) {
				if ( !is_null( $question ) && $question != "" ) {
					$questionRepo->insert([
						"interview_template_id" => $this->interviewTemplate->id,
						"question_type_id" => 1,
						"placement" => $i,
						"body" => $question
					]);
				}
				$i++;
			}
		}
	}

	public function update()
	{
		if ( $this->validateAccount() ) {
			$interviewTemplateRepo = $this->load( "interview-template-repository" );
			$questionRepo = $this->load( "question-repository" );

			$interviewTemplate = $interviewTemplateRepo->get( [ "*" ], [ "id" => $this->request->params( "id" ) ], "single" );
			$interviewTemplate->questions = $questionRepo->getAllByInterviewTemplateID( $interviewTemplate->id );

			$interviewTemplateRepo->update(
				[
					"name" => $this->request->post( "name" ),
					"description" => $this->request->post( "description" )
				],
				[ "id" => $this->request->params( "id" ) ]
			);

			// Process new question order and values
			if ( $this->request->post( "update_existing_questions" ) != "" ) {
				$existing_questions = $this->request->post( "existing_question" );
				if ( is_array( $existing_questions ) ) {
					$iteration = 1;
					foreach ( $existing_questions as $id => $body ) {
						// Ensure question body isn't empty
						if ( !is_null( $body ) && $body != "" ) {
							$questionRepo->update(
								[ "body" => $body, "placement" => $iteration ],
								[ "id" => $id, "interview_template_id" => $this->request->params( "id" ) ]
							);
						}
						$iteration++;
					}
					$this->request->addFlashMessage( "success", "Questions updated" );
				}
			}

			$this->request->setFlashMessages();
		}
	}

	public function addQuestion()
	{
		if ( $this->validateAccount() ) {
			$interviewTemplateRepo = $this->load( "interview-template-repository" );
			$interviewTemplate = $interviewTemplateRepo->get(
				[ "*" ],
				[ "id" => $this->request->params( "id" ), "organization_id" => $this->organization->id ],
				"single"
			);

			$questionRepo = $this->load( "question-repository" );
			$interviewTemplate->questions = $questionRepo->get(
				[ "*" ],
				[ "interview_template_id" => $this->request->params( "id" ) ],
				"single"
			);

			$question = $questionRepo->insert([
				"interview_template_id" => $this->request->params( "id" ),
				"question_type_id" => 1,
				"placement" => count( $interviewTemplate->questions ) + 1,
				"body" => $this->request->post( "body" )
			]);

			$this->request->addFlashMessage( "success", "Question added" );
			$this->request->setFlashMessages();
		}
	}
}
