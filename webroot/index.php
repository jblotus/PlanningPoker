<?php
// autoloader
require '../vendor/autoload.php';

use Aura\Sql\ExtendedPdo;
use Aura\Router\RouterFactory;
use Aura\Web\WebFactory;

$pdo = new ExtendedPdo(
    'mysql:host=localhost;dbname=pp',
    'root',
    ''
);

$router = (new RouterFactory)->newInstance(); 

//without this $_SERVER does not get added to globals
//http://www.php.net/manual/en/ini.core.php#ini.auto-globals-jit
$_SERVER;

$webFactory = new WebFactory($GLOBALS);

$request = $webFactory->newRequest();
$response = $webFactory->newResponse();

$appRouter = new jblotus\PlanningPoker\Router($router, $request, $response);
$route = $appRouter->initialize();

if (!$route) {
    // no route object was returned
    echo "No application route was found for that URL path.";
    exit();     //replace this when under test
}
// get the route params
$params = $route->params;

// extract the controller callable from the params
$controller = $params['controller'];
unset($params['controller']);

// invoke the callable
$controller($params);
