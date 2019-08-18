<?php

namespace Model\Services;

class QuickBoi
{
    private $dao;
    private $id_string;
    private $application_namespace;
    private $entity_namepsace;
    private $entity_class_name;
    private $entity_properties = [];
    private $mapper_namespace;
    private $mapper_class_name;
    private $repository_class_name;
    private $repository_namespace;
    private $query;
    private $query_log_file;

    public function __construct( \PDO $dao )
    {
        $this->dao = $dao;
    }

    public function buildEntity( $entity_name )
    {
        $this->buildModelNames( $entity_name );
        $this->createEntityFile();
        $this->createRepositoryFile();
        $this->createMapperFile();
        $this->createTable();

        // Register the repository with in services.json
        $this->registerService(
            "\\Model\\Services\\",
            $entity_name . "-repository",
            [ "dao", "entity-factory" ]
        );

        $this->logQuery();

        return;
    }

    public function buildModelNames( $entity_name )
    {
        if ( preg_match( "/[^a-zA-Z -]/", $entity_name ) ) {
            throw new \Exception( "String can only contain characters a-z upper or lower case, spaces, and hyphens (-)" );
        }

        // Create the string with which classes will be registered with the container
        // and with which class names and tables will be built.
        $id_string = $this->formatIdString( $entity_name );
        $this->setIdString( $id_string );

        // build entity, repository, and mapper class name
        $this->setEntityClassName( $this->formatClassNameFromIdString( $id_string ) )
            ->setRepositoryClassName( $this->formatClassNameFromIdString( $id_string ) )
            ->setMapperClassName( $this->formatClassNameFromIdString( $id_string ) );

        return;
    }

    public function setApplicationNamespace( $namespace )
    {
        $this->application_namespace = $namespace;
        return $this;
    }

    private function getApplicationNamespace()
    {
        if ( isset( $this->application_namespace ) === false ) {
            throw new \Exception( "Application namespace has not been set" );
        }

        return $this->application_namespace;
    }

    private function formatIdString( $string )
    {
        return strtolower( preg_replace( "/[-]+/", "-", preg_replace( "/[\s]+/", "-", trim( $string ) ) ) );
    }

    private function setIdString( $string )
    {
        $this->id_string = $string;
        return $this;
    }

    private function formatClassNameFromIdString( $id_string )
    {
        return str_replace( " ", "", ucwords( str_replace( "-", " ", $id_string ) ) );
    }

    private function formatClassInstanceVariableFromIdString( $id_string )
    {
        return lcfirst( $this->formatClassNameFromIdString( $id_string ) );
    }

    public function setEntityNamespace( $namespace )
    {
        $this->entity_namespace = $namespace;
        return $this;
    }

    public function getEntityNamespace()
    {
        if ( !isset( $this->entity_namespace ) ) {
            throw new \Exception( "Entity namespace has not been set" );
        }

        return $this->entity_namespace;
    }

    public function setEntityClassName( $name )
    {
        $this->entity_class_name = $name;
        return $this;
    }

    public function setRepositoryNamespace( $namespace )
    {
        $this->repository_namespace = $namespace;
        return $this;
    }

    public function getRepositoryNamespace()
    {
        if ( !isset( $this->repository_namespace ) ) {
            throw new \Exception( "Repository namespace has not been set" );
        }

        return $this->repository_namespace;
    }

    private function setRepositoryClassName( $name )
    {
        $this->repository_class_name = $name . "Repository";
        return $this;
    }

    public function setMapperNamespace( $namespace )
    {
        $this->mapper_namespace = $namespace;
        return $this;
    }

    public function getMapperNamespace()
    {
        if ( !isset( $this->mapper_namespace ) ) {
            throw new \Exception( "Mapper namespace has not been set" );
        }

        return $this->mapper_namespace;
    }

    private function setMapperClassName( $name )
    {
        $this->mapper_class_name = $name . "Mapper";
        return $this;
    }

    private function formatDirnameFromClassName( $class_name )
    {
        $dirname = str_replace( "\\", "/", $class_name );
        return $dirname;
    }

    private function checkFile( $filename )
    {
        if ( file_exists( $filename ) ) {
            throw new \Exception( "File '{$filename}' already exists" );
        }

        return;
    }

    private function createFile( $filename, $contents )
    {
        $this->checkFile( $filename, $contents );
        file_put_contents( $filename, $contents );

        return;
    }

    private function createEntityFile()
    {
        $filename = $this->formatDirnameFromClassName( $this->getApplicationNamespace() ) . "/" . $this->formatDirnameFromClassName( $this->entity_namespace ) . "/" . $this->entity_class_name . ".php";
        $filename = "./" . preg_replace( "/[\/]+/", "/", $filename );

        $property_string = "";
        foreach ( $this->entity_properties as $key => $property ) {
            if ( array_key_exists( "property_name", $property ) ) {
                $property_string = $property_string . "\tpublic $" . $property[ "property_name" ] . ";\n";
            }
        }

        $contents = "<?php\n\nnamespace {$this->getEntityNamespace()};\n\nuse Contracts\EntityInterface;\n\nclass {$this->entity_class_name} implements EntityInterface\n{\n{$property_string}}";

        $this->createFile( $filename, $contents );

        return;
    }

    private function createRepositoryFile()
    {
        $filename = $this->formatDirnameFromClassName( $this->getApplicationNamespace() ) . "/" . $this->formatDirnameFromClassName( $this->repository_namespace ) . "/" . $this->repository_class_name . ".php";
        $filename = "./" . preg_replace( "/[\/]+/", "/", $filename );

        $contents = "<?php\n\nnamespace {$this->getRepositoryNamespace()};\n\nclass {$this->repository_class_name} extends Repository\n{\n\n}";

        $this->createFile( $filename, $contents );

        return;
    }

    private function createMapperFile()
    {
        $filename = $this->formatDirnameFromClassName( $this->getApplicationNamespace() ) . "/" . $this->formatDirnameFromClassName( $this->mapper_namespace ) . "/" . $this->mapper_class_name . ".php";
        $filename = "./" . preg_replace( "/[\/]+/", "/", $filename );

        $contents = "<?php\n\nnamespace {$this->getMapperNamespace()};\n\nclass {$this->mapper_class_name} extends DataMapper\n{\n\n}";

        $this->createFile( $filename, $contents );

        return;
    }

    public function addEntityPropery( array $property )
    {
        $this->entity_properties[] = $property;

        return;
    }

    public function getEntityProp()
    {
        return $this->entity_properties;
    }

    public function formatTableName( $string )
    {
        return strtolower( str_replace( "-", "_", $string ) );
    }

    private function formatColumnName( $column_name )
    {
        $column_name = preg_replace( "/[_]+/", "_", str_replace( "-", "_", str_replace( " ", "_", strtolower( trim( $column_name ) ) ) ) );
        return $column_name;
    }

    public function setEngine( $engine )
    {
        $this->engine = $engine;
        return $this;
    }

    public function getEngine()
    {
        if ( isset( $this->engine ) === false ) {
            throw new \Exception( "Engine has not been chosen" );
        }

        return $this->engine;
    }

    private function setQuery( $query )
    {
        $this->query = $query;
        return $this;
    }

    private function getQuery()
    {
        if ( isset( $this->query ) === false ) {
            throw new \Exception( "Query has not been created" );
        }

        return $this->query;
    }

    private function buildQueryFromProperties( array $properties )
    {
        $query = "CREATE TABLE `{$this->formatTableName( $this->id_string )}` ( {*columns*}{*primary_key*}) engine = {$this->getEngine()}";
        $columns = "";
        foreach ( $properties as $property ) {
            $column = $column_name = $data_type = $is_primary = $auto_increment = "";
            $is_null = "NOT NULL";
            foreach ( $property as $key => $property_component ) {
                switch ( $key ) {
                    case "property_name":
                        $column_name = $this->formatColumnName( $property_component );
                        break;
                    case "data_type":
                        $data_type = $property_component;
                        break;
                    case "value_length":
                        if ( $property_component != "" ) {
                            $data_type = $data_type . "({$property_component})";
                        }
                        break;
                    case "is_null":
                        $is_null = "NULL";
                        break;
                    case "auto_increment";
                        $auto_increment = " AUTO_INCREMENT";
                        break;
                }

                $column = "`{$column_name}` {$data_type} {$is_null}{$auto_increment} , ";

                if ( array_key_exists( "is_primary", $property ) ) {
                    $query = preg_replace( "/\{\*primary_key\*\}/", "PRIMARY KEY (`{$column_name}`)", $query );
                }
            }

            $columns = $columns . $column;
        }

        $query = preg_replace( "/\{\*columns\*\}/", $columns, $query );

        return $query;
    }

    private function createTable()
    {
        $query = $this->buildQueryFromProperties( $this->entity_properties );
        $this->setQuery( $query );

        $db = $this->dao;
        $sql = $db->prepare( $query );
        $sql->execute();

        return;
    }

    public function setQueryLogFile( $filename )
    {
        $this->query_log_file = $filename;
        return $this;
    }

    public function getQueryLogFile()
    {
        if ( isset( $this->query_log_file ) === false ) {
            throw new \Exception( "Database updates filename not set." );
        }

        return $this->query_log_file;
    }

    private function logQuery()
    {
        $query = $this->getQuery();

        if ( !file_exists( $this->getQueryLogFile() ) ) {
            file_put_contents( $this->getQueryLogFile(), "\n" . $query . ";" );

            return;
        }

        $content = file_get_contents( $this->getQueryLogFile() );
        $content .= "\n" . $query. ";";

        file_put_contents( $this->getQueryLogFile(), $content );
    }

    public function buildService( $service_name, $dependencies )
    {
        if ( !is_array( $dependencies ) ) {
            $dependencies = [];
        }

        $this->createServiceFile( $service_name, $dependencies );

        $id_strings = [];
        foreach ( $dependencies as $dependency ) {
            $dependency_parts = explode( " ", $dependency );
            $id_strings[] = $dependency_parts[ 1 ];
        }
        $this->registerService( "\\Model\\Services\\", $service_name, $id_strings );
    }

    private function createServiceFile( $service_name, $dependencies )
    {
        // Create the class name
        $class_name = $this->formatClassNameFromIdString( $service_name );

        // Create the file name
        $filename = "App/Model/Services/" . $class_name . ".php";

        // Format the dependencies into class names and variables for constructor
        // Save arguments from the constructor
        $formatted_dependencies = "";
        $dependency_properties = "";
        $class_properties = "";
        $id_strings = [];

        $i = 1;
        foreach ( $dependencies as $dependency ) {
            $dependency_parts = explode( " ", $dependency );
            $namespace = ( $dependency_parts[ 0 ] == "\Model\Services\\" ? "" : $dependency_parts[ 0 ] );
            $id_string = $dependency_parts[ 1 ];
            $id_strings[] = $dependency_parts[ 1 ];

            if ( $i < count( $dependencies ) ) {
                // Add commas after the variable names except on last dependency
                $formatted_dependencies .= "\n\t\t" . $namespace . $this->formatClassNameFromIdString( $id_string ) . " \${$this->formatClassInstanceVariableFromIdString( $id_string )},";
                $class_properties .= "\n\tpublic \${$this->formatClassInstanceVariableFromIdString( $id_string )};";
            } else {
                $formatted_dependencies .= "\n\t\t" . $namespace . $this->formatClassNameFromIdString( $id_string ) . " \${$this->formatClassInstanceVariableFromIdString( $id_string )}\n\t";
                $class_properties .= "\n\tpublic \${$this->formatClassInstanceVariableFromIdString( $id_string )};\n";
            }

            $dependency_properties .= "\t\t\$this->{$this->formatClassInstanceVariableFromIdString( $id_string )} = \${$this->formatClassInstanceVariableFromIdString( $id_string )};\n";
            $i++;
        }
        $contents = "<?php\n\nnamespace Model\Services;\n\nclass {$class_name}\n{{$class_properties}\n\tpublic function __construct({$formatted_dependencies}) {\n{$dependency_properties}\t}\n}";

        $this->createFile( $filename, $contents );

        return;
    }

    private function registerService( $namespace, $service_name, $dependencies, $package = null )
    {
        $service_register = json_decode( file_get_contents( "App/Conf/services.json" ), true );

        if ( !empty( $dependencies ) ) {
            $service_register[ "services" ][ $namespace ][ $service_name ] = $dependencies;
            ksort( $service_register[ "services" ][ $namespace ], SORT_STRING );

            file_put_contents( "App/Conf/services.json", json_encode( $service_register, JSON_PRETTY_PRINT ) );

            return;
        }

        $service_register[ "services" ][ $namespace ][] = $service_name;

        ksort( $service_register[ "services" ][ $namespace ], SORT_STRING );

        file_put_contents( "App/Conf/services.json", json_encode( $service_register, JSON_PRETTY_PRINT ) );

        return;
    }

    public function registerAlias( $alias, $id_string )
    {
        $service_register = json_decode( file_get_contents( "App/Conf/services.json" ), true );

        $service_register[ "aliases" ][ $alias ] = $id_string;

        ksort( $service_register[ "aliases" ], SORT_STRING );
        
        file_put_contents( "App/Conf/services.json", json_encode( $service_register, JSON_PRETTY_PRINT ) );

        return;
    }
}
