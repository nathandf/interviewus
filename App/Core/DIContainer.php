<?php
/*
* Dependency Injector
*/
namespace Core;

class DIContainer
{
    protected $services = [];
    protected $cached_services = [];

    public function __construct()
    {
        $container = $this;
    }

    public function register( $service_name, callable $service )
    {
        $this->services[ $service_name ] = $service;
    }

    public function getService( $service_name, $cached_services = false )
    {
        // Check if a service by this name exisits.
        if ( !array_key_exists( $service_name, $this->services ) ) {
            throw new \Exception( "Service '{$service_name}' cannot be found." );
        }

        $this->cacheService( $service_name );

        // Returning existing service
        return $this->services[ $service_name ]();
    }

    private function cacheService( $service )
    {
        // Cache the service
        $this->cached_services[] = $service;
    }

    public function listServices()
    {
        if ( empty( $this->services ) ) {
            throw new \Exception( "No services are registered with the DIContainer" );
        }
        return array_keys( $this->services );
    }

    public function listCachedServices()
    {
        return $this->cached_services;
    }
}
