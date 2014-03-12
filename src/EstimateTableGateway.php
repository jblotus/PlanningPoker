<?php

namespace jblotus\PlanningPoker;

use Aura\Sql\ExtendedPdoInterface;

class EstimateTableGateway
{
    private $pdo;
    
    public function __construct(ExtendedPdoInterface $pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function selectAllFromEstimates()
    {
        $statement = "SELECT id, point_value FROM estimates LIMIT 100";
        $bind = array();
        return $this->pdo->fetchAll($statement, $bind);
    }
    
    public function insertEstimateWithPoints($pointValue)
    {
        $statement = $this->pdo->prepare('INSERT INTO estimates (point_value) VALUES (:point_value)');
        $statement->bindValue('point_value', $pointValue);
        $statement->execute();
        
        return $this->pdo->lastInsertId();
    }
}