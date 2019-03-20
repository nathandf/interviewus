<?php

define( "DS", "/" ); // Directory seperator
// constants REL and HOME defined in the router class

// site info
define( 'SITE_PRETTY', 'InterviewUs.net' );
define( 'SITE', 'interviewus.net' );
define( 'EMAIL', 'interviewus@gmail.com' );
define( 'PHONE_NUMBER', '(812) 276-3172' );

// resources **NOTE** Always end the path to the resources with a forward slash
define( "CONFIGS", "config/" );
define( "JS_SCRIPTS", "public/js/" );
define( "MODELS", "App/models/" );
define( "VIEWS", "App/Views/" );
define( "TEMPLATES", VIEWS . "templates/" );
define( "CONTROLLERS", "App/controllers/" );

define( "HELPERS", "App/Helpers/" );

// time
define( "YEAR", date( 'Y' ) );
define( "TODAY",  date( 'Y-m-d' ) );

// user
define( 'USER_IP', $_SERVER[ "REMOTE_ADDR" ] );
