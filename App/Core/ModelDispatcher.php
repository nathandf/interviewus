<?php

namespace Core;

class ModelDispatcher
{
    public $modelFactory;
    private $class_name;
    private $method;

    public function __construct( ModelFactory $modelFactory )
    {
        $this->modelFactory = $modelFactory;
    }

    public function dispatch( $command, Request $request, DIContainer $container )
    {
        if ( is_null( $command[ 0 ] ) ) {
            $command[ 0 ] = "Home";
        }

        $this->formatModelCommand( $command );

        $model = $this->modelFactory->build( $this->getClassName(), $request, $container );

        $model->{$this->getMethod()}( $command[ 2 ] );

        return $model;
    }

    private function formatModelCommand( $command )
    {
        $model_parts = explode( ":", $command[ 0 ] );

        $this->setClassName( $model_parts[ 0 ] );

        if ( isset( $model_parts[ 1 ] ) === false ) {
            $this->setMethod( null );

            return $this;
        }

        $this->setMethod( $model_parts[ 1 ] );

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
