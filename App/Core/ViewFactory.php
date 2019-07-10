<?php

namespace Core;

class ViewFactory
{
    public function build( $view_name, Model $model, DIContainer $container )
    {
        return "New View";
    }
}
