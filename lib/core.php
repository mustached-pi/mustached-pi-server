<?php

/*
 * ©2013 Alfio Emanuele Fresta
 */

/*
 * Standard PSR-0 autoloader, adapted from:
 * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
 */
spl_autoload_register( function () {
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    require 'lib/vendor/' . $fileName;
});

/*
 * Create the user session
 */
$session = new \MPi\Entity\Session(@$_COOKIE['sid']);
setcookie('sid', $session->id);