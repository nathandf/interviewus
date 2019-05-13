<?php
/*
* Front Controller
*/
// autoloading native classes and third party libraries. Check composer.json for details
require_once( "App/vendor/autoload.php" );
require_once( "App/Helpers/debug.php" );

// Dependency injection container
$container = new Core\DI_Container;

// Load services using DI_Container
require_once( "App/Conf/services.php" );

// Initialize configs
$config = $container->getService( "config" );

// Session and token handling
$session = $container->getService( "session" );

// Error handling
error_reporting( E_ALL );

// routing
$Router = $container->getService( "router" );

// routes
require_once( "App/Conf/routes.php" );

$request = $Router->dispatch( $_SERVER[ "QUERY_STRING" ] );
$controller_name = $request[ "controller" ];

$controller = new $controller_name( $container, $config, $session, $request[ "params" ] );
$controller->$request[ "method" ]();
