<?php

namespace jblotus\PlanningPoker;

class EstimateTableGatewayTest extends \PHPUnit_Framework_TestCase
{
    private $gateway;
    private $pdo;
  
    private $anyPointValue;
    private $anyEstimateId;
    private $anyEstimates;
    private $anyStatement;
  
    public function setUp()
    {
        $this->anyPointValue = 1234;
        $this->anyEstimateId = 40982;
        $this->anyEstimates = array(
            array('id' => 123, 'point_value' => 456),
            array('id' => 1234, 'point_value' => 4567)
        );
        
        $this->anyStatement = $this->getMockBuilder('PDOStatement')
            ->setMethods(array('bindValue', 'execute'))
            ->getMock();
        
        $this->pdo = $this->getMockBuilder('Aura\Sql\ExtendedPdoInterface')
            ->getMock();
        
        $this->gateway = new EstimateTableGateway($this->pdo);
    }
    
    public function testInsertEstimateWithPointsDoesIt()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO estimates (point_value) VALUES (:point_value)')
            ->will($this->returnValue($this->anyStatement));
        
        $this->anyStatement->expects($this->once())
            ->method('bindValue')
            ->with('point_value', $this->anyPointValue);
        
        $this->pdo->expects($this->once())
            ->method('lastInsertId')
            ->will($this->returnValue($this->anyEstimateId));
        
        $actual = $this->gateway->insertEstimateWithPoints($this->anyPointValue);
        $this->assertEquals($this->anyEstimateId, $actual);
    }
    
    public function testSelectAllFromEstimatesDoesIt()
    {
        $this->pdo->expects($this->once())
            ->method('fetchAll')
            ->with('SELECT id, point_value FROM estimates LIMIT 100')
            ->will($this->returnValue($this->anyEstimates));
        
        $actual = $this->gateway->selectAllFromEstimates();
        $this->assertEquals($this->anyEstimates, $actual);
    }
}
