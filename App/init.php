<?php
/*
* Front Controller
*/
// autoloading native classes and third party libraries. Check composer.json for details
require_once( "App/vendor/autoload.php" );
require_once( "App/Helpers/debug.php" );

// Dependency injection container
$container = new Core\DIContainer;

// Load services using DIContainer
require_once( "App/Conf/services.php" );

// Error handling
error_reporting( E_ALL );

// routing
$Router = $container->getService( "router" );

$request = $Router->dispatch( $_SERVER[ "QUERY_STRING" ] );

$controller_name = $request[ "controller" ];
$method = $request[ "method" ];
$params = $request[ "params" ];

$controller = new $controller_name(
	$container,
	$container->getService( "config" ),
	$container->getService( "session" ),
	$container->getService( "request" ),
	$params
);

$command = $controller->$method();

$model = $modelFactory->build( $command );

$view = $viewFactory->build( $command, $model );
