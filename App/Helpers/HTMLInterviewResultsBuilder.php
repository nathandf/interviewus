<?php

namespace Helpers;

use Model\Entities\Interview;

class HTMLInterviewResultsBuilder
{
	private $html_interview_results;

	public function build( Interview $interview )
	{
		$this->validateInterview( $interview );

		foreach ( $interview->questions as $question ) {
			$this->addQuestionAnswer( $question, $question->answer );
		}

		return $this->html_interview_results;
	}

	private function addQuestionAnswer( $question, $answer )
	{
		$this->html_interview_results = $this->html_interview_results . "<p><b>Question {$question->placement}:</b></p>\n<p><b>{$question->body}</b></p>\n\n<p><i>{$answer->body}</i></p>\n";
	}

	private function validateInterview( Interview $interview )
	{
		// Make sure this interview has questions
		if ( isset( $interview->questions ) == false || !is_array( $interview->questions ) ) {
			throw new \Exception( "Interview questions are not set" );
		}


		foreach ( $interview->questions as $question ) {
			if ( isset( $question->answer ) == false  ) {
				throw new \Exception( "Answer for question ({$question->id}) is not net" );
			}
		}
	}
}
