<?php

namespace Helpers;

use Model\Entities\Interview;

class HtmlInterviewResultsBuilder
{
	private $html_interview_results;

	public function build( Interview $interview )
	{
		$this->validateInterviewQuestions( $interview );

		foreach ( $interview->questions as $question ) {
			if ( isset( $question->answer ) == false  ) {
				$question->answer = null;
			}
			$this->addQuestionAnswer( $question, $question->answer );
		}

		return $this->html_interview_results;
	}

	private function addQuestionAnswer( $question, $answer )
	{
		if ( !is_null( $answer ) ) {
			$answer = $answer->body;
		} else {
			$answer = "Not answered";
		}

		$divider = "<div style='width: 100%; background: #AAAAAA; height: 1px;'></div>";

		$this->html_interview_results = $this->html_interview_results . "<p>Question {$question->placement}:</p>\n<p><b>{$question->body}</b></p>\n\n<p><i>{$answer}</i></p>\n{$divider}";
	}

	private function validateInterviewQuestions( Interview $interview )
	{
		// Make sure this interview has questions
		if ( isset( $interview->questions ) == false || !is_array( $interview->questions ) ) {
			throw new \Exception( "Interview questions are not set" );
		}
	}
}
