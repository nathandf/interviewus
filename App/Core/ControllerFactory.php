<?php

namespace Core;

class ControllerFactory
{
    public function build( $controller, Request $request, DIContainer $container )
    {
        return new $controller( $request, $container );
    }
}
