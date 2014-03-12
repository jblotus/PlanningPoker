<?php
// autoloader
require 'vendor/autoload.php';

$estimateRecorder = new jblotus\PlanningPoker\EstimateRecorder();
echo $estimateRecorder->recordEstimate();