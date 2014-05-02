<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

require_once dirname(__DIR__) . '/vendor/autoload.php';

define('APP_ROOT', dirname(dirname(__FILE__)));
define('VIEW_ROOT', APP_ROOT . '/views/');

  var_dump(error_reporting());