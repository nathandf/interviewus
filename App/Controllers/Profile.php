<?php

namespace Controllers;

use \Core\Controller;

class Profile extends Controller
{
    public function before()
    {

    }

    public function indexAction()
    {
        echo( "Profile" );
    }
}
