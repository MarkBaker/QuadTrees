<?php

namespace QuadTrees;

/**
 *
 * Autoloader for QuadTree classes
 *
 * @package QuadTrees
 * @copyright  Copyright (c) 2014 Mark Baker (https://github.com/MarkBaker/QuadTrees)
 * @license    https://opensource.org/licenses/MIT          MIT
 */
class Autoloader
{
    /**
     * Register the Autoloader with SPL
     *
     */
    public static function Register()
    {
        if (function_exists('__autoload')) {
            //    Register any existing autoloader function with SPL, so we don't get any clashes
            spl_autoload_register('__autoload');
        }

        //    Register ourselves with SPL
        return spl_autoload_register(['QuadTrees\\Autoloader', 'Load']);
    }


    /**
     * Autoload a class identified by name
     *
     * @param    string    $pClassName    Name of the object to load
     */
    public static function Load($pClassName)
    {
        if ((class_exists($pClassName, false)) || (strpos($pClassName, 'QuadTrees\\') !== 0)) {
            // Either already loaded, or not a QuadTrees class request
            return false;
        }

        $pClassFilePath = __DIR__ . DIRECTORY_SEPARATOR .
                          'src' . DIRECTORY_SEPARATOR .
                          str_replace(['QuadTrees\\', '\\'], ['', '/'], $pClassName) .
                          '.php';

        if ((file_exists($pClassFilePath) === false) || (is_readable($pClassFilePath) === false)) {
            // Can't load
            return false;
        }
        require($pClassFilePath);
    }
}
