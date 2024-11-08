<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitf87a23ff2b95a20edf49ad4bd70e90cb
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitf87a23ff2b95a20edf49ad4bd70e90cb', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitf87a23ff2b95a20edf49ad4bd70e90cb', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitf87a23ff2b95a20edf49ad4bd70e90cb::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
