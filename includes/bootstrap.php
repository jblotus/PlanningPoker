<?php


//without this $_SERVER does not get added to globals
//http://www.php.net/manual/en/ini.core.php#ini.auto-globals-jit
$_SERVER;
$_ENV;


// autoloader
require '../vendor/autoload.php';

define('APP_ROOT', dirname(dirname(__FILE__)));
define('VIEW_ROOT', APP_ROOT . '/views/');