<?php
/*
* Services and dependency injection on instanciation
*/

// Core
$container->register( "config", function() {
	$config = new Conf\Config;
	return $config;
} );

$container->register( "error", function() use ( $container ) {
	$error = new Core\Error( $container );
	return $error;
} );

$container->register( "session", function() {
    $session = new Core\Session;
    return $session;
} );

$container->register( "request", function() use ( $container ) {
	$request = new Core\Request;
	return $request;
} );

$container->register( "input", function() {
    $obj = new \Core\Input;
    return $obj;
} );

$container->register( "input-validator", function() {
    $obj = new \Core\InputValidator;
    return $obj;
} );

$container->register( "router", function() use ( $container ) {
	$router = new Core\Router( $container->getService( "config" ) );
	return $router;
} );

$container->register( "view", function() use( $container ) {
	$view = new Core\View( $container );
	return $view;
} );

$container->register( "smarty", function() {
	$smarty = new Smarty();
	return $smarty;
} );

$container->register( "templating-engine", function() use ( $container ) {
	$templatingEngine = $container->getService( "smarty" );
	return $templatingEngine;
} );

$container->register( "logger", function() use ( $container ) {
	$Config = $container->getService( "config" );
	$logsDir = $Config::$configs[ "logs_directory" ];
	$logger = new Katzgrau\KLogger\Logger( $logsDir );
	return $logger;
} );

// Database access object

$container->register( "pdo", function() use ( $container ) {
	$conf = $container->getService( "config" );
	$pdo = new \PDO(
		"mysql:host={$conf->configs[ "db" ][ "{$conf->getEnv()}" ][ "host" ]}; dbname={$conf->configs[ "db" ][ "{$conf->getEnv()}" ][ "dbname" ]};",
		$conf->configs[ "db" ][ "{$conf->getEnv()}" ][ "user" ],
		$conf->configs[ "db" ][ "{$conf->getEnv()}" ][ "password" ]
	);
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	return $pdo;
} );

$container->register( "dao", function() use ( $container ) {
	$databaseAccessObject = $container->getService( "pdo" );
	return $databaseAccessObject;
} );

// Services

$container->register( "entity-factory", function() {
	$factory = new \Model\Services\EntityFactory;
	return $factory;
} );

$container->register( "quick-boi", function() use ( $container ) {
	$service = new \Model\Services\QuickBoi(
		$container->getService( "dao" )
	);
	return $service;
} );

// NOTE pattern to registrer a REPOSITORY
// $container->register( "x-repository", function() use ( $container ) {
// 	$repo = new \Model\Services\XRepository(
// 		$container->getService( "dao" ),
// 		$container->getService( "entity-factory" )
// 	);
// 	return $repo;
// } );

$container->register( "industry-repository", function() use ( $container ) {
	$repo = new \Model\Services\IndustryRepository(
		$container->getService( "dao" ),
		$container->getService( "entity-factory" )
	);
	return $repo;
} );

$container->register( "video-repository", function() use ( $container ) {
	$repo = new \Model\Services\VideoRepository(
	    $container->getService( "dao" ),
	    $container->getService( "entity-factory" )
	);
	return $repo;
} );

// Helpers

$container->register( "access-control", function() {
	$helper = new \Helpers\AccessControl;
	return $helper;
} );

$container->register( "time-converter", function() use ( $container ) {
	$helper = new \Helpers\TimeConverter;
	return $helper;
} );

$container->register( "time-manager", function() {
	$timeManager = new \Helpers\TimeManager;
	return $timeManager;
} );

$container->register( "time-zone-helper", function() {
	$helper = new \Helpers\TimeZoneHelper;
	return $helper;
} );

$container->register( "access-control", function() {
	$accessControl = new \Helpers\AccessControl;
	return $accessControl;
} );

$container->register( "image-manager", function() {
	$imageManager = new \Helpers\ImageManager;
	return $imageManager;
} );

$container->register( "video-manager", function() {
	$helper = new \Helpers\VideoManager;
	return $helper;
} );
