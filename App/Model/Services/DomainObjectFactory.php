<?php

namespace Model\Services;

class DomainObjectFactory
{
    public function build( $type, $namespace = "\\Model\\DomainObjects\\" )
    {
        if ( $type == "" ) {
            throw new \Exception( "Invalid Domain Object Type: \"$type\"." );
        }

        $class = $namespace . ucwords( $type );

        if ( class_exists( $class ) ) {
            return new $class();
        } else {
            throw new \Exception( "Class {$type}" );
        }
    }

    public function replicateEntity( \Contracts\EntityInterface $object )
    {
        $class = get_class( $object );
        return new $class();
    }
}
