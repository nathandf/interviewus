<?php

namespace Model\Models;

use Core\Model;

class InterviewTemplate extends ProfileModel
{
	public function create()
	{
		parent::validateAccount();

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
