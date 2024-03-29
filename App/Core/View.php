<?php

namespace Core;

/**
 * Class View
 * @package Core
 */
class View extends CoreObject
{
    protected $model;
    protected $container;
    private $configs;
    public $request;
    public $template;
    private $data = [];

    public function __construct( Model $model, DIContainer $container )
    {
        $this->model = $model;
        $this->setContainer( $container );

        $this->configs = $container->getService( "config" )->configs;

        $this->request = $model->request;
        
        $this->setTemplatingEngine();
    }

    public function redirect( $redirect_url, $http_response_code = 200, $external_redirect = false )
    {
        if ( $external_redirect ) {
            header( "Location: " . $redirect_url );
            exit();
        }
        http_response_code( $http_response_code );
        header( "Location: " . HOME . $redirect_url );
        exit();
    }

    protected function setTemplatingEngine()
    {
        $this->templatingEngine = $this->load( "templating-engine" );

        if ( $this->templatingEngine ) {
            // All templates are pulled from here
            $this->templatingEngine->template_dir = $this->configs[ "templating" ][ "templates" ];
            $this->templatingEngine->compile_dir = $this->configs[ "templating" ][ "cached_templates" ];

            // Set csrf token
            $this->templatingEngine->assign( "csrf_token", $this->request->csrf_token );

            // Constants
            $this->templatingEngine->assign( "HOME", HOME );
            $this->templatingEngine->assign( "JS_SCRIPTS", "public/js/" );
            $this->templatingEngine->assign( "APP_NAME", $this->configs[ "app-details" ][ "app_name" ] );
            $this->templatingEngine->assign( "CUSTOMER_SUPPORT_NUMBER", $this->configs[ "app-details" ][ "app_customer_support_number" ] );
            $this->templatingEngine->assign( "CUSTOMER_SUPPORT_EMAIL", $this->configs[ "app-details" ][ "app_customer_support_email" ] );
        }
    }

    public function addApplicationError( $error_messages )
    {
        if ( is_array( $error_messages ) ) {
            foreach ( $error_messages as $message ) {
                if ( !is_string( $message ) ) {
                    throw new \Exception( "Error message must be of type string; Type '" . gettype( $message ) . "' provided" );
                }

                $this->application_error_messages[] = $message;
            }
        }

        $this->application_error_messages[] = $error_messages;

        $this->assign( "application_errors", $this->application_error_messages );
    }

    public function setErrorMessages( array $error_messages )
    {
        $this->assign( "error_messages", $error_messages );
    }

    public function addErrorMessage( $index, $message )
    {
        $this->data[ "error_messages" ][ $index ] = $message;
    }

    public function setFlashMessages( array $flash_messages )
    {
        $this->assign( "flash_messages", $flash_messages );
    }

    /**
     * @param string $template
     */
    public function setTemplate( $template )
    {
        $this->template = $template;
    }

    public function render( $data = null )
    {
        if ( $this->templatingEngine ) {
            // assigning data from the views to the templating engine
            foreach ( $this->data as $key => $value ) {
                $this->templatingEngine->assign( $key, $value );
            }

            if ( isset( $this->template ) ) {
                // render view
                ob_start();
                $this->templatingEngine->display( "App/templates/". $this->template );
                ob_end_flush();
            }

            return;
        }

        if ( pathinfo( $this->template, PATHINFO_EXTENSION ) == "tpl" ) {
            throw new \Exception( "Cannot render .tpl file without a templating engine" );
        }
        
        require_once( $this->configs[ "templating" ][ "templates" ] . $this->template );

        return;
    }

    /**
     * @param string $index
     * @param mixed $data
     * @param bool $sanitize
     */
    public function assign( $index, $data, $sanitize = true )
    {
        $this->data[ $index ] = $data;
    }

    public function render404()
    {
        $this->setTemplate( "404.shtml" );
    }

    public function render403()
    {
        $this->render( "403.shtml" );
    }

    public function respondWithJson( $data )
    {
        if ( is_string( $data ) ) {
            echod( $data );
        }

        echod( json_encode( $data ) );
    }
}
