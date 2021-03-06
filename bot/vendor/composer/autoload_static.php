<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit523e4d5a6a881e92ca82d827a09a7bfd
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'DG\\Twitter\\Exception' => __DIR__ . '/..' . '/dg/twitter-php/src/Twitter.php',
        'DG\\Twitter\\OAuth\\Consumer' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter\\OAuth\\Exception' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter\\OAuth\\Request' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter\\OAuth\\SignatureMethod' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter\\OAuth\\SignatureMethod_HMAC_SHA1' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter\\OAuth\\SignatureMethod_PLAINTEXT' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter\\OAuth\\SignatureMethod_RSA_SHA1' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter\\OAuth\\Token' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter\\OAuth\\Util' => __DIR__ . '/..' . '/dg/twitter-php/src/OAuth.php',
        'DG\\Twitter\\Twitter' => __DIR__ . '/..' . '/dg/twitter-php/src/Twitter.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit523e4d5a6a881e92ca82d827a09a7bfd::$classMap;

        }, null, ClassLoader::class);
    }
}
