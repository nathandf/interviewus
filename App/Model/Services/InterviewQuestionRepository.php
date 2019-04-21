<?php

namespace Model\Services;

class InterviewQuestionRepository extends Repository
{
	public function getAllByInterviewID( $interview_id, $order_by_placement = true )
	{
		if ( $order_by_placement ) {
			return $this->mapper->getAllByInterviewIDOrderPlacementAsc( $interview_id );
		}

		return $this->mapper->get( [ "*" ], [ "interview_id" => $interview_id ], "array" );
	}
}
