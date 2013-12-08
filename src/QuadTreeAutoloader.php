<?php

QuadTreeAutoloader::Register();

/**
 *
 * Autoloader for QuadTree classes
 *
 * @package QuadTree
 * @copyright  Copyright (c) 2013 Mark Baker (https://github.com/MarkBaker/QuadTrees)
 * @license    http://www.gnu.org/licenses/lgpl-3.0.txt    LGPL
 */
class QuadTreeAutoloader
{
    /**
     * Register the Autoloader with SPL
     *
     */
    public static function Register() {
        if (function_exists('__autoload')) {
            //    Register any existing autoloader function with SPL, so we don't get any clashes
            spl_autoload_register('__autoload');
        }
        //    Register ourselves with SPL
        return spl_autoload_register(array('QuadTreeAutoloader', 'Load'));
    }    //    function Register()


    /**
     * Autoload a class identified by name
     *
     * @param    string    $pClassName        Name of the object to load
     */
    public static function Load($className) {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        if ($namespace !== 'QuadTrees')
            return false;
        $fileName = __DIR__ . DIRECTORY_SEPARATOR . $className . '.php';

        require $fileName;
    }    //    function Load()

}