<?php

namespace Model\Mappers;

class QuestionMapper extends DataMapper
{
	public function getAllByInterviewTemplateIDOrderPlacementAsc( $interview_template_id )
	{
		$sql = $this->DB->prepare( "SELECT * FROM `{$this->getTable()}` WHERE interview_template_id = :interview_template_id ORDER BY placement ASC" );
		$sql->bindParam( ":interview_template_id", $interview_template_id );
		$sql->execute();

		$questions = [];

		while ( $response = $sql->fetch( \PDO::FETCH_ASSOC ) ) {
            $question = $this->build( $this->formatEntityNameFromTable() );
            $this->populate( $question, $response );
            $questions[] = $question;
        }

		return $questions;
	}
}
