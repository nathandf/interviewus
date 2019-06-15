<?php

namespace Model\Services;

use Core\DI_Container;
use Model\Mappers\DataMapper;
use Contracts\RepositoryInterface;

abstract class Repository implements RepositoryInterface
{
    protected $entityName;
    protected $mapper;
    protected $mapperNamespace;

    public function __construct( $dao, $entityFactory )
    {
        $this->buildEntityName();
        $this->setMapperNamespace( "\Model\Mappers\\" );

        $this->setMapper(
            $this->buildMapper( $dao, $entityFactory )
        );
    }

    protected function setMapper( DataMapper $mapper )
    {
        $this->mapper = $mapper;
    }

    protected function getMapper()
    {
        if ( isset( $this->mapper ) == false ) {
            throw new \Exception( "'Mapper' is not set" );
        }

        return $this->mapper;
    }

    protected function buildMapper( $dao, $entityFactory )
    {
        $mapperName = $this->buildMapperName();

        $mapper = new $mapperName( $dao, $entityFactory );

        return $mapper;
    }

    protected function buildEntityName()
    {
        // Derive the name of the mapper and entity from the class name of this repository
        $repositoryClassName = explode( "\\", get_class( $this ) );
        $entityName = $this->mapperNamespace . str_replace( "Repository", "", end( $repositoryClassName ) );
        // Set entity name
        $this->setEntityName( $entityName );
    }

    protected function buildMapperName()
    {
        return $this->mapperNamespace . $this->entityName . "Mapper";
    }

    protected function setEntityName( $entityName )
    {
        $this->entityName = $entityName;
    }

    protected function setMapperNamespace( $namespace )
    {
        $this->mapperNamespace = $namespace;
    }

    // Basic CRUD
    public function insert( array $key_values, $return_object = true )
    {
        $mapper = $this->getMapper();
        $entity = $mapper->build( $this->entityName );

        return $mapper->insert( $key_values, $return_object );
    }

    public function get( array $columns, $key_values = [], $return_type = "array" )
    {
        if ( !is_array( $key_values ) ) {
            throw new \Exception( "key_values argument must be an array" );
        }

        if ( func_num_args() > 2 ) {
            $return_type = func_get_args()[ 2 ];
        }

        $mapper = $this->getMapper();
        $result = $mapper->get( $columns, $key_values, $return_type );

        return $result;
    }

    public function update( array $columns_to_update, array $where_columns )
    {
        $mapper = $this->getMapper();
        $mapper->update( $columns_to_update, $where_columns );
    }

    public function delete( array $keys, array $values )
    {
        $mapper = $this->getMapper();
        $mapper->delete( $keys, $values );
    }

    public function deleteEntities( $entities )
    {
        $mapper = $this->getMapper();

        // If entities is an array, iterate through the array and delete all
        // entities from the database
        if ( is_array( $entities ) ) {
            foreach ( $entities as $entity ) {
                $this->validateEntity( $entity );
                // Delete this entity
                $mapper->delete(
                    [ "id" ],
                    [ $entity->id ]
                );
            }

            return;
        }

        // If entites arg is a single entity, then delete this entity
        $this->validateEntity( $entities );
        $mapper->delete( [ "id" ], [ $entities->id ] );
        return;
    }

    private function validateEntity( $entity )
    {

        if ( !is_a( $entity, "Model\\Entities\\{$this->entityName}" ) ) {
            throw new \Exception( "Entity invalid. Must be of class 'Model\\Entities\\{$this->entityName}' - '" . get_class( $entity ) . "' provided" );
        }

        return;
    }
}
