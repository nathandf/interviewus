<?php

namespace Controllers;

use \Core\Controller;

class Test extends Controller
{
    public function indexAction()
    {
           
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
