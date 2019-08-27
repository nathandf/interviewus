<?php
/**
 * Core Controller Class
 */
namespace Core;

abstract class Controller extends CoreObject
{
    protected $container;
    protected $config;
    protected $request;
    protected $params;
    protected $action_filter_data = [];

    public function __construct( Request $request, DIContainer $container )
    {
        $this->setContainer( $container );
        $this->config = $this->container->getService( "config" );
        $this->request = $request;
        $this->params = $request->params();
    }

    // Every time a method is called on Controller class, check if before and after
    // methods exist and run them respectively
    public function __call( $name, $args )
    {
        $method = $name . "Action";
        if ( method_exists( $this, $method ) ) {
            // Run the before method
            $before = $this->before();

            // If a command is returned in the before method, then return it in
            // the controller method.
            if ( is_array( $before ) ) {
                return $before;
            }

            // If a command is returned in the primary method, return it
            $primary = call_user_func_array( [ $this, $method ], $args );
            if ( is_array( $primary ) ) {
                return $primary;
            }

            // If a command is returned in the after method, return it
            $after = $this->after();
            if ( is_array( $after ) ) {
                return $after;
            }
        } else {
            throw new \Exception( "Method \"$method\" is not a method of class " . get_class( $this ), 404 );
        }
    }

    // Remove "Action" from the end of the method names on which you don't want the
    // before or action methods to be invoked automatically
    protected function before()
    {}

    protected function after()
    {}

    protected function requireParam( $param )
    {
        if ( !isset( $this->params[ $param ] ) ) {
            $this->view->render404();
            exit();
        }
    }

    protected function issetParam( $param )
    {
        if ( !isset( $this->params[ $param ] ) ) {
            return false;
        }
        return true;
    }
}
