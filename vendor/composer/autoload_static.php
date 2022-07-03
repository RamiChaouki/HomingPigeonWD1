<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit755f25b08d598e80889af73c4d08d6ad
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit755f25b08d598e80889af73c4d08d6ad::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit755f25b08d598e80889af73c4d08d6ad::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit755f25b08d598e80889af73c4d08d6ad::$classMap;

        }, null, ClassLoader::class);
    }
}