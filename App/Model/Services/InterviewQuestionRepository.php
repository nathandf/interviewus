<?php

namespace Model\Services;

use Model\Entities\Interview;

class InterviewQuestionRepository extends Repository
{
	public function getAllByInterview( Interview $interview, $order_by_placement = true )
	{
		if ( $order_by_placement ) {
			return $this->mapper->getAllByInterviewOrderPlacementAsc( $interview );
		}

		return $this->mapper->get( [ "*" ], [ "interview_id" => $interview_id ], "array" );
	}
}
