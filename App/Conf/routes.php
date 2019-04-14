<?php

// routes
$Router->add( "", [ "controller" => "home", "action" => "index" ] );
$Router->add( "{action}", [ "controller" => "home" ] );
$Router->add( "{path:[a-zA-Z0-9-/]+}/{controller:[a-zA-Z0-9-]*}/{id:[0-9]+}/{action:[a-zA-Z0-9-]*}" );

$Router->add( "{controller:i}/{token:[a-zA-Z0-9-]+}", [ "action" => "index" ] );

// webhooks
$Router->add( "{path:webhooks/twilio}/{sid:[a-zA-Z0-9-]+}/{controller}/{action:[a-zA-Z0-9-]*}" );

$Router->add( "{controller}/{action:[a-zA-Z0-9-]*}" );
$Router->add( "{path:[a-zA-Z0-9-/]+}/{controller}/{action:[a-zA-Z0-9-]*}" );
