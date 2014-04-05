<?php
// autoloader
require '../vendor/autoload.php';

define('APP_ROOT', dirname(dirname(__FILE__)));
define('VIEW_ROOT', APP_ROOT . '/views/');

use Aura\Di\Container;
use Aura\Di\Factory;
use Aura\Sql\ExtendedPdo;
use Aura\Router\RouterFactory;
use Aura\Web\WebFactory;
use Aura\View\Finder;
use Aura\View\Helper;
use Aura\View\Manager;
use Aura\View\Template;
use GuzzleHttp\Client as HttpClient;
use jblotus\PlanningPoker\View;
use jblotus\PlanningPoker\Controller;
use jblotus\PlanningPoker\Dispatcher;


//without this $_SERVER does not get added to globals
//http://www.php.net/manual/en/ini.core.php#ini.auto-globals-jit
$_SERVER;
$_ENV;


$di = new Container(new Factory);
$di->set('database', new ExtendedPdo('mysql:host=localhost;dbname=pp', 'root', ''));

$di->set('webfactory', function() {    
     
    return new WebFactory($GLOBALS);
});

$di->set('response', function() use($di) {         
    $webFactory = $di->get('webfactory');
    return $webFactory->newResponse();
});

$di->set('request', function() use ($di) {
    $webFactory = $di->get('webfactory');
    return $webFactory->newRequest();
});

$di->set('router', function() use ($di) {            
    $router = (new RouterFactory)->newInstance(); 
    $request = $di->get('request');
    $response = $di->get('response');
    
    return new jblotus\PlanningPoker\Router($router, $request, $response);
});

$di->set('view', function() {
    $viewManager = new Manager(
        new Template,   // template factory
        new Helper,     // bare-bones helper object
        new Finder,     // view-template finder
        new Finder      // layout-template finder
    );
    $layoutTemplate = function () {
        require_once VIEW_ROOT . '/layouts/default.html.php';       
    };
    
    return new View($viewManager, $layoutTemplate);
});

$di->set('pivotalService', function() use ($di) {
    $client = new HttpClient(); 
    return $client;
});

$di->set('controller', function() use ($di) {
    $view = $di->get('view');
    $request = $di->get('request');
    $pivotalService = $di->get('pivotalService');
    $response = $di->get('response');
    return new Controller($view, $request, $pivotalService, $response);
});

$di->set('dispatcher', function() {
    return new Dispatcher;
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

$controller = $di->get('controller');
$response = $controller->$params['action']();

$dispatcher = $di->get('dispatcher');
$dispatcher->outputResponseToBrowser($response);
