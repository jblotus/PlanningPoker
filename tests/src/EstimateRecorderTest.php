<?php

namespace jblotus\PlanningPoker;

class EstimateRecorderTest extends \PHPUnit_Framework_TestCase
{
  public function setUp()
  {
    $this->estimateRecorder = new EstimateRecorder();
  }
  
  public function testRecorderDoesIt()
  {
    $actual = $this->estimateRecorder->recordEstimate();
    $this->assertEquals('foo', $actual);
  }
}
