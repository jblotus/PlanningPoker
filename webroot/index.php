<?php
// autoloader
require '../vendor/autoload.php';

use Aura\Sql\ExtendedPdo;
use Aura\Router\RouterFactory;

$pdo = new ExtendedPdo(
    'mysql:host=localhost;dbname=pp',
    'root',
    ''
);


$router = (new RouterFactory)->newInstance(); 

$appRouter = new jblotus\PlanningPoker\Router($router);
$appRouter->initialize();
