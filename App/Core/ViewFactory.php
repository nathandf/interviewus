<?php

namespace Core;

class ViewFactory
{
    public function build( $view_name, Model $model, DIContainer $container )
    {
        $view = "\\Views\\{$view_name}";
        
        return new $view( $model, $container );
    }
}
