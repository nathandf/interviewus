<?php

namespace Core;

class ModelFactory
{
    public function build( $model_name, Request $request, DIContainer $container )
    {
        $model = "\\Model\\Models\\{$model_name}";

        return new $model;
    }
}
