<?php

namespace Model\Services;

class QuestionRepository extends Repository
{
	public function getAllByInterviewTemplateID( $interview_template_id, $order_by_placement = true )
	{
		if ( $order_by_placement ) {
			return $this->mapper->getAllByInterviewTemplateIDOrderPlacementAsc( $interview_template_id );
		}

		return $this->mapper->get( [ "*" ], [ "interview_template_id" => $interview_template_id ], "array" );
	}
}
