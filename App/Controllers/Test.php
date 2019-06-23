<?php

namespace Controllers;

use \Core\Controller;

class Test extends Controller
{
    public function indexAction()
    {
        $this->load( "interview-repository" )->update(
			[
				"status" => "complete",
				"conversation_id" => null
			],
			[ "id" => 119 ]
		);
    }
}
