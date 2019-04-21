<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit78dc808f4f283431745450a0bb20ab63
{
    public static $files = array (
        'f084d01b0a599f67676cffef638aa95b' => __DIR__ . '/..' . '/smarty/smarty/libs/bootstrap.php',
        '3f8bdd3b35094c73a26f0106e3c0f8b2' => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/SendGrid.php',
        '9dda55337a76a24e949fbcc5d905a2c7' => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/helpers/mail/Mail.php',
        '5d7e2090c9a3b69b2ff2fceddeadca94' => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/helpers/contacts/Recipients.php',
        '04a9a7b26fb79fbd712347cc9bc9d9be' => __DIR__ . '/..' . '/sendgrid/sendgrid/lib/helpers/stats/Stats.php',
        'a38330d2f51f82a186b58186cfe87a71' => __DIR__ . '/../..' . '/Conf/constants.php',
    );

    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'Views\\' => 6,
        ),
        'T' => 
        array (
            'Twilio\\' => 7,
        ),
        'S' => 
        array (
            'Services\\' => 9,
            'SendGrid\\' => 9,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
        'M' => 
        array (
            'Model\\' => 6,
            'Mappers\\' => 8,
        ),
        'K' => 
        array (
            'Katzgrau\\KLogger\\' => 17,
        ),
        'H' => 
        array (
            'Helpers\\' => 8,
        ),
        'E' => 
        array (
            'Entities\\' => 9,
        ),
        'C' => 
        array (
            'Core\\' => 5,
            'Controllers\\' => 12,
            'Contracts\\' => 10,
            'Conf\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Views\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Views',
        ),
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/Twilio',
        ),
        'Services\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Model/Services',
        ),
        'SendGrid\\' => 
        array (
            0 => __DIR__ . '/..' . '/sendgrid/php-http-client/lib',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
        'Model\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Model',
        ),
        'Mappers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Model/Mappers',
        ),
        'Katzgrau\\KLogger\\' => 
        array (
            0 => __DIR__ . '/..' . '/katzgrau/klogger/src',
        ),
        'Helpers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Helpers',
        ),
        'Entities\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Model/Entities',
        ),
        'Core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Core',
        ),
        'Controllers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Controllers',
        ),
        'Contracts\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Contracts',
        ),
        'Conf\\' => 
        array (
            0 => __DIR__ . '/../..' . '/Conf',
        ),
    );

    public static $classMap = array (
        'Katzgrau\\KLogger\\Logger' => __DIR__ . '/..' . '/katzgrau/klogger/src/Logger.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit78dc808f4f283431745450a0bb20ab63::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit78dc808f4f283431745450a0bb20ab63::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit78dc808f4f283431745450a0bb20ab63::$classMap;

        }, null, ClassLoader::class);
    }
}
