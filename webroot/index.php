<?php
require dirname(__DIR__) . '/includes/bootstrap.php';
require dirname(__DIR__) . '/includes/services.php';

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
