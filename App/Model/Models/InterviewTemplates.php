<?php

namespace Model\Models;

class InterviewTemplates extends ProfileModel
{
	public function index()
	{
		if ( $this->validateAccount() ) {
			$interviewTemplateRepo = $this->load( "interview-template-repository" );
	        $this->interviewTemplates = $interviewTemplateRepo->get( [ "*" ], [ "organization_id" => $this->organization->id ] );
		}
	}

	public function browse()
	{
		if ( $this->validateAccount() ) {
			// TODO load importable interviews
		}
	}

	public function duplicate()
	{
		if ( $this->validateAccount() ) {
			// Load the interview
			$interviewTemplateRepo = $this->load( "interview-template-repository" );
			$interviewTemplate = $interviewTemplateRepo->get( [ "*" ], [ "id" => $this->request->post( "interview_template_id" ) ], "single" );

			// Load the questions
			$questionRepo = $this->load( "question-repository" );
			$interviewTemplate->questions = $questionRepo->get( [ "*" ], [ "interview_template_id" => $this->request->post( "interview_template_id" ) ] );

			// Create the new interview template
            $this->newInterviewTemplate = $interviewTemplateRepo->insert([
                "name" => $interviewTemplate->name . " - Copy",
                "description" => $interviewTemplate->description,
                "organization_id" => $interviewTemplate->organization_id,
                "industry_id" => $interviewTemplate->industry_id
            ]);

			// Add the questions
            foreach ( $interviewTemplate->questions as $question ) {
                $questionRepo->insert([
                    "interview_template_id" => $this->newInterviewTemplate->id,
                    "question_type_id" => $question->question_type_id,
                    "placement" => $question->placement,
                    "body" => $question->body
                ]);
            }

			$this->request->addFlashMessage( "success", "Duplicated: {$this->newInterviewTemplate->name}" );
			$this->request->setFlashMessages();
		}
	}
}
