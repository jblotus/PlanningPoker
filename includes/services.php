<?php

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
use jblotus\PlanningPoker\AuthService;

$di = new Container(new Factory);
$di->set('database', function() {
    return new ExtendedPdo('mysql:host=localhost;dbname=pp', 'root', '');
});

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
    $authService = $di->get('authservice');
    $pivotalService = $di->get('pivotalService');
    $response = $di->get('response');    
    return new Controller($view, $request, $authService, $pivotalService, $response);
});

$di->set('dispatcher', function() {
    return new Dispatcher;
}); 

$di->set('session', $di->lazyNew('Aura\Session\Session'));
 
$di->params['Aura\Session\CsrfTokenFactory']['randval'] = $di->lazyNew('Aura\Session\Randval');
 
$di->params['Aura\Session\Session'] = array(
    'segment_factory' => $di->lazyNew('Aura\Session\SegmentFactory'),
    'csrf_token_factory' => $di->lazyNew('Aura\Session\CsrfTokenFactory'),
    'cookies' => $_COOKIE,
);
 
$di->params['Aura\Session\Randval']['phpfunc'] = $di->lazyNew('Aura\Session\Phpfunc');
 
$di->params['Aura\Session\Segment'] = array(
    'session' => $di->lazyGet('session'),
);

$di->set('lightopenid', function() use ($di) {
    $lightOpenId = new LightOpenID('planning-poker-91022.use1.nitrousbox.com:4000');
    
    $lightOpenId->identity = 'https://www.google.com/accounts/o8/id';
    $lightOpenId->required = array(
        'namePerson/first',
        'namePerson/last',
        'contact/email',
    );
    return $lightOpenId;
});

$di->set('authservice', function() use ($di) {
    return new AuthService($di->get('session'), $di->get('lightopenid'));
});
