<?php

namespace Core;

class ModelFactory
{
    public function build( $model_name, Request $request, DIContainer $container )
    {
        $model = "\\Model\\Models\\{$model_name}";
        vdumpd( new $model );
        return new $model;
    }
}
