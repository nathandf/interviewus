<?php

namespace Model\Mappers;

class InterviewQuestionMapper extends DataMapper
{
	public function getAllByInterviewIDOrderPlacementAsc( $interview_id )
	{
		$sql = $this->DB->prepare( "SELECT * FROM `{$this->getTable()}` WHERE interview_id = :interview_id ORDER BY placement ASC" );
		$sql->bindParam( ":interview_id", $interview_id );
		$sql->execute();

		$interview_questions = [];

		while ( $response = $sql->fetch( \PDO::FETCH_ASSOC ) ) {
            $interview_question = $this->build( $this->formatEntityNameFromTable() );
            $this->populate( $interview_question, $response );
            $interview_questions[] = $interview_question;
        }

		return $interview_questions;
	}
}
