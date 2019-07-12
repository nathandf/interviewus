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
	$modelFactory = $container->getService( "model-factory" );
	$model = $modelFactory->build( $command[ 0 ], $request, $container );
	$model->{$command[ 1 ]}( $command[ 2 ] );

	// Dispatch View
	$viewFactory = $container->getService( "view-factory" );
	$view = $viewFactory->build( $command[ 0 ], $model, $container );
	$view->{$command[ 1 ]}( $command[ 2 ] );
	$view->render();
}
