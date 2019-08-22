<?php

$routes = [
	[ "", [ "controller" => "home", "action" => "index" ] ],
	[ "{action}", [ "controller" => "home" ] ],
	[ "{path:[a-zA-Z0-9-/]+}/{controller:[a-zA-Z0-9-]*}/{id:[0-9]+}/{action:[a-zA-Z0-9-]*}", [] ],
	[ "{controller:i}/{token:[a-zA-Z0-9-]+}/{action:[a-zA-Z0-9-]*}", [] ],
	[ "{controller:reset-password}/{token:[a-zA-Z0-9-.]+}/{action:[a-zA-Z0-9-]*}", [] ],
	[ "{path:webhooks/twilio}/{sid:[a-zA-Z0-9-]+}/{controller}/{action:[a-zA-Z0-9-]*}", [] ],
	[ "{controller}/{action:[a-zA-Z0-9-]*}", [] ],
	[ "{path:[a-zA-Z0-9-/]+}/{controller}/{action:[a-zA-Z0-9-]*}", [] ]
];
