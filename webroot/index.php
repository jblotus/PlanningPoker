<?php
// autoloader
require '../vendor/autoload.php';

use Aura\Di\Container;
use Aura\Di\Factory;
use Aura\Sql\ExtendedPdo;
use Aura\Router\RouterFactory;
use Aura\Web\WebFactory;


$di = new Container(new Factory);
$di->set('database', new ExtendedPdo('mysql:host=localhost;dbname=pp', 'root', ''));

$di->set('webfactory', function() {    
    //without this $_SERVER does not get added to globals
    //http://www.php.net/manual/en/ini.core.php#ini.auto-globals-jit
    $_SERVER;
    
    return new WebFactory($GLOBALS);
});

$di->set('router', function() use ($di) {
        
    $webFactory = $di->get('webfactory');
    
    $router = (new RouterFactory)->newInstance(); 
    $request = $webFactory->newRequest();
    $response = $webFactory->newResponse();
    
    return new jblotus\PlanningPoker\Router($router, $request, $response);
});

$appRouter = $di->get('router');


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
