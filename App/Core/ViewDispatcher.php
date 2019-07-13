<?php

namespace Core;

class ViewDispatcher
{
    public $viewFactory;
    private $class_name;
    private $method;

    public function __construct( ViewFactory $viewFactory )
    {
        $this->viewFactory = $viewFactory;
    }

    public function dispatch( $command, Model $model, DIContainer $container )
    {
        if ( is_null( $command[ 1 ] ) ) {
            $command[ 1 ] = "Error:e500";
        }

        $this->formatViewCommand( $command );

        $view = $this->viewFactory->build( $this->getClassName(), $model, $container );

        $view->{$this->getMethod()}( $command[ 2 ] );

        return $view;
    }

    private function formatViewCommand( $command )
    {
        $view_parts = explode( ":", $command[ 1 ] );

        $this->setClassName( $view_parts[ 0 ] );

        if ( isset( $view_parts[ 1 ] ) === false ) {
            $this->setMethod( null );

            return $this;
        }

        $this->setMethod( $view_parts[ 1 ] );

        return $this;
    }

    private function setClassName( $class_name )
    {
        $this->class_name = $class_name;

        return $this;
    }

    private function getClassName()
    {
        if ( !isset( $this->class_name ) ) {
            throw new \Exception( "Class name not set" );
        }

        return $this->class_name;
    }

    private function setMethod( $method )
    {
        if ( is_null( $method ) ) {
            $this->method = "index";

            return $this;
        }

        $this->method = $method;

        return $this;
    }

    private function getMethod()
    {
        if ( !isset( $this->method ) ) {
            throw new \Exception( "Method not set" );
        }

        return $this->method;
    }

}
