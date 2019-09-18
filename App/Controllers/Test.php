<?php

namespace Controllers;

use \Core\Controller;

class Test extends Controller
{
    public function indexAction()
    {
        $entities = [];
        $id_strings = [ "account", "account-user", "user", "address", "phone", "image", "video", "file", "unsubscribe" ];

        foreach ( $id_strings as $id_string ) {
            $repo = $this->load( "{$id_string}-repository" );
            $entities[ $id_string ] = $repo->get( [ "*" ] )[ 0 ];
        }

        vdumpd( json_encode( $entities, JSON_PRETTY_PRINT ) );
    }
    
    public function phpInfo()
    {
        phpinfo();
    }
	
	public function testNumber()
	{
		return [ null, "Sms:send", null, null ];
	}
}
