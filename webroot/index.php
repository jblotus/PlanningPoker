<?php
// autoloader
require '../vendor/autoload.php';

use Aura\Sql\ExtendedPdo;

$pdo = new ExtendedPdo(
    'mysql:host=localhost;dbname=pp',
    'root',
    ''
);

$estimateTable = new jblotus\PlanningPoker\EstimateTableGateway($pdo);

$pointValue = mt_rand();
echo $estimateTable->insertEstimateWithPoints($pointValue);

echo '<pre>' . print_r($estimateTable->selectAllFromEstimates(), 1) . '</pre>';