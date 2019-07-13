<?php

    function vdump( $input ) {
        echo "<pre>";
        var_dump( $input );
        echo "</pre><br>";
    }

    function vdumpd( $input ) {
        vdump( $input );
        die();
        exit();
    }

    function echod( $input ) {
        echo $input;
        die();
        exit();
    }

    class QuickTime
    {
        public static $time_start;
        public static $time_end;

        public static function start()
        {
            self::$time_start = microtime( true );
        }

        public static function end()
        {
        self::$time_end = microtime( true );
        die( "Execution took " . ( self::$time_end - self::$time_start ) . " seconds");
        }
    }

    $qt = new QuickTime;
?>
