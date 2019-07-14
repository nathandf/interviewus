<?php

// autoloading native classes and third party libraries. Check composer.json for details
require_once( "App/vendor/autoload.php" );
require_once( "App/Helpers/debug.php" );

// Error handling
error_reporting( E_ALL );

// Dependency injection container
$container = new Core\DIContainer;

// Load client requst
$request = $container->getService( "request" );

// Load the router
$Router = $container->getService( "router" );

// Get the route
$route = $Router->dispatch( $request );

// Use the route to build the controller
$controllerFactory = $container->getService( "controller-factory" );

$controller = $controllerFactory->build(
	$route[ "controller" ],
	$request->setParams( $route[ "params" ] ), // returns $this (\Core\Request)
	$container
);

$command = $controller->{$route[ "method" ]}();

if ( !is_null( $command ) ) {
	// Dispatch Model
	$modelDispatcher = $container->getService( "model-dispatcher" );
	$model = $modelDispatcher->dispatch( $command, $request, $container );

	// Dispatch View
	$viewDispatcher = $container->getService( "view-dispatcher" );
	$view = $viewDispatcher->dispatch( $command, $model, $container );
}
