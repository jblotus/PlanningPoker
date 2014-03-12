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
echo $estimateTable->recordEstimate($pointValue);

echo '<pre>' . print_r($estimateTable->getAllEstimates(), 1) . '</pre>';