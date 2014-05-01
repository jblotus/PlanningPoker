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
use jblotus\PlanningPoker\View;
use jblotus\PlanningPoker\Controller;
use jblotus\PlanningPoker\Dispatcher;
use jblotus\PlanningPoker\AuthService;

$di = new Container(new Factory);

$di->set('database', $di->lazyNew('Aura\Sql\ExtendedPdo'));
$di->params['Aura\Sql\ExtendedPdo'] = array(
    'dsn' => 'mysql:host=localhost;dbname=pp',
    'username' => 'root',
    'password' => null
);

$di->set('webFactory', $di->lazyNew('Aura\Web\WebFactory'));
$di->params['Aura\Web\WebFactory'] = array('globals' => $GLOBALS);

$di->set('response', function() use($di) {         
    $webFactory = $di->get('webFactory');
    return $webFactory->newResponse();
});

$di->set('request', function() use ($di) {
    $webFactory = $di->get('webFactory');
    return $webFactory->newRequest();
});

$di->set('router', $di->lazyNew('jblotus\PlanningPoker\Router'));

$di->params['jblotus\PlanningPoker\Router'] = array(
    'router' => (new RouterFactory)->newInstance(),
    'request' => $di->lazyGet('request'),
    'response' => $di->lazyGet('response')
);

$di->set('viewManager', function() {
     return new Manager(
        new Template,   // template factory
        new Helper,     // bare-bones helper object
        new Finder,     // view-template finder
        new Finder      // layout-template finder
    );
});

$di->set('view', $di->lazyNew('jblotus\PlanningPoker\View'));

$di->params['jblotus\PlanningPoker\View'] = array(
    'viewManager' => $di->lazyGet('viewManager'),
    'layoutTemplate' => function () {
      require_once VIEW_ROOT . '/layouts/default.html.php';       
    }
);

$di->set('pivotalService', $di->lazyNew('GuzzleHttp\Client'));


$di->set('controller', $di->lazyNew('jblotus\PlanningPoker\Controller'));
$di->params['jblotus\PlanningPoker\Controller'] = array(
    'view' => $di->lazyGet('view'),
    'request' => $di->lazyGet('request'),
    'authService' => $di->lazyGet('authService'),
    'pivotal' => $di->lazyGet('pivotalService'),
    'response' => $di->lazyGet('response')
);

$di->set('dispatcher', $di->lazyNew('jblotus\PlanningPoker\Dispatcher')); 

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

$di->set('authService', $di->lazyNew('jblotus\PlanningPoker\AuthService'));
$di->params['jblotus\PlanningPoker\AuthService'] = array(
    'session' => $di->lazyGet('session'),
    'lightOpenId' => $di->lazyGet('lightopenid')
);
