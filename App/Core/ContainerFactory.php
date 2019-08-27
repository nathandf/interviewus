<?php
/*
* Dependency Injector
*/
namespace Core;

class ContainerFactory
{
    public $container;

    public function build()
    {
        // Create the container
        $this->container = new DIContainer;

        // Load the json services file and json_decode results to assoc array
        // and load the container with anonymous functions that create the services
        $register = file_get_contents( "App/Conf/services.json" );
        $this->init( json_decode( $register, true ) );

        return $this->container;
    }

    private function init( array $register )
    {
        // Register service classes
        $this->registerServices( $register[ "services" ] );

        // Register Packages
        $this->registerPackages( $register[ "packages" ] );

        // Register Aliases
        $this->registerAliases( $register[ "aliases" ] );
    }

    private function registerServices( array $services )
    {
        // Register service classes
        foreach ( $services as $namespace => $services ) {
            foreach ( $services as $index => $service ) {
                // Register a class without dependencies
                if ( is_numeric( $index ) ) {
                    // Get class name
                    $class = $this->serviceIndexToClass( $namespace, $service );

                    // Register anonymous function that returns the class
                    $this->container->register( $service, function () use ( $class ) {
                        return new $class;
                    } );

                    continue;
                }
                // Register class with dependencies
                // Get class name
                $class = $this->serviceIndexToClass( $namespace, $index );

                // Register anonymous function that returns the class
                $container = $this->container;

                $this->container->register( $index, function () use ( $class, $service, $container  ) {
                    $dependencies = [];
                    foreach ( $service as $dependency ) {
                        // If the dependency is a this container, pass reference of $this
                        if ( $dependency == "container" ) {
                            $dependencies[] = &$container;
                            continue;
                        }
                        $dependencies[] = $container->getService( $dependency );
                    }
                    // Use splat operator "..." to unpack dependencies
                    return new $class( ...$dependencies );
                } );
            }
        }
    }

    private function registerPackages( array $packages )
    {
        foreach ( $packages as $prefix => $package ) {

            foreach ( $package as $namespace => $services ) {

                foreach ( $services as $index => $dependencies ) {

                    // Register class with dependencies
                    // Get class name
                    $class = $this->serviceIndexToClass( $namespace, $index );
                    $index = $prefix . "-" . $index;
                    // Register anonymous function that returns the class
                    $container = $this->container;
                    $this->container->register( $index, function () use ( $class, $dependencies, $container  ) {
                        foreach ( $dependencies as $key => $dependency ) {

                            // If the dependency is a this container, pass reference of $this
                            if ( $dependency == "container" ) {
                                $dependencies[ $key ] = &$container;
                                continue;
                            }

                            $dependencies[ $key ] = $container->getService( $dependency );
                        }

                        // Use splat operator "..." to unpack dependencies
                        return new $class( ...$dependencies );
                    } );
                }
            }
        }
    }

    private function registerAliases( array $aliases )
    {
        $container = $this->container;
        foreach ( $aliases as $abstraction => $implementation ) {
            $this->container->register( $abstraction, function () use ( $implementation, $container ) {
                // Use splat operator "..." to unpack dependencies
                return $container->getService( $implementation );
            } );
        }
    }

    // Turns a name space and an index into a class name
    private function serviceIndexToClass( $namespace, $index )
    {
        $class_name = str_replace( ' ', '', ucwords( str_replace( '-', ' ', $index ) ) );

        return $namespace . $class_name;
    }
}
