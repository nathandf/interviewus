<?php

namespace Model\Models;

class Profile extends ProfileModel
{
	public $errors = [];
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

	public function shareInterview()
	{
		if ( $this->validateAccount() ) {
			// Compile all interview questions and their answers into one large object
			$interviewRepo = $this->load( "interview-repository" );
			$interview = $interviewRepo->get( [ "*" ], [ "id" => $this->request->post( "interview_id" ) ], "single" );

			$intervieweeRepo = $this->load( "interviewee-repository" );
			$interview->interviewee = $intervieweeRepo->get( [ "*" ], [ "id" => $interview->interviewee_id ], "single" );

			$positionRepo = $this->load( "position-repository" );
			$interview->position = $positionRepo->get( [ "*" ], [ "id" => $interview->position_id ], "single" );

			$interviewQuestionRepo = $this->load( "interview-question-repository" );
			$interview->questions = $interviewQuestionRepo->get( [ "*" ], [ "interview_id" => $interview->id ] );

			$intervieweeAnswerRepo = $this->load( "interviewee-answer-repository" );
			foreach ( $interview->questions as $question ) {
				$question->answer = $intervieweeAnswerRepo->get( [ "*" ], [ "interview_question_id" => $question->id ], "single" );
			}

			// Build the inteview results into a nice html form to be used in the
			// email template
			$htmlInterviewResultsBuilder = $this->load( "html-interview-results-builder" );
			$html_interview_results = $htmlInterviewResultsBuilder->build( $interview );

			// Parse interview recipients
			$recipients = explode( ",", strtolower( str_replace( ", ", ",", $this->request->post( "recipients" ) ) ) );

			$domainObjectFactory = $this->load( "domain-object-factory" );
			$emailBuilder = $this->load( "email-builder" );
			$mailer = $this->load( "mailer" );

			if ( is_array( $recipients ) ) {
				$i = 0;
				foreach ( $recipients as $email ) {
					// Only send an email to the first 5 recipients...
					if ( $i < 5 ) {
						// ... and the email provided is a valid email address
						if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
							// Build the email context to be used by the email template
							$emailContext = $domainObjectFactory->build( "EmailContext" );
							$emailContext->addProps([
								"user" =>  $this->user->getFullName(),
								"interviewee" => $interview->interviewee->getFullName(),
								"interview_results" => $html_interview_results
							]);

							// Notify admin of user feedback
							$resp = $mailer->setTo( $email, "Contact" )
								->setFrom( $this->user->email, $this->user->getFullName() )
								->setSubject( "Interview Results | {$interview->interviewee->getFullName()} | {$interview->position->name}" )
								->setContent( $emailBuilder->build( "interview-results.html", $emailContext ) )
								->mail();
						}
					}
					$i++;
				}
			}
		}
	}
}
