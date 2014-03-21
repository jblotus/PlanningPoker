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
use jblotus\PlanningPoker\View;
use jblotus\PlanningPoker\Controller;


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

$di->set('controller', function() use ($di) {
    $view = $di->get('view');
    return new Controller($view);
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
echo $controller->$params['action']();
