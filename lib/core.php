<?php

/*
 * ©2013 Alfio Emanuele Fresta
 */

/*
 * DEBUGGING - Show Errors
 */
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

/*
 * Standard PSR-0 autoloader, adapted from:
 * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
 */
spl_autoload_register( function ( $className ) {
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    include 'lib/vendor/' . $fileName;
});

/*
 * Loads the configuration files specified in $_toLoad
 */
$_toLoad = ['database', 'constants'];
foreach ( $_toLoad as $_confFile ) {
    if ( !file_exists( 'conf/' . $_confFile . '.conf.php' ) ) {
        throw new \PBase\Exception\General(1005);
    }
    require 'conf/' . $_confFile . '.conf.php';
}

/*
 * Loads the function files specified in $_toLoad
 */
$_toLoad = ['pages', 'strings'];
foreach ( $_toLoad as $_libFile ) {
    if ( !file_exists( 'lib/functions/' . $_libFile . '.php' ) ) {
        throw new \PBase\Exception\General(1005);
    }
    require 'lib/functions/' . $_libFile . '.php';
}

/*
 * Connects to the configured database
 * (sample conf. in /conf/database.conf.php.sample)
 */
$db = new \PBase\Database\Connection(
    $conf['db']['dsn'],
    $conf['db']['username'],
    $conf['db']['password']
);

if ( !$db ) {
    throw new \PBase\Exception\General(1006);
}

/*
 * Create the user session
 */
$session = new \MPi\Entity\Session(@$_COOKIE['sid']);
setcookie('sid', $session->id);