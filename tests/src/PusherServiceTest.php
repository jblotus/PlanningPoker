<?php

namespace jblotus\PlanningPoker;

class PusherServiceTest extends \PHPUnit_Framework_TestCase
{
    private $pusher;
    private $pusherLib;
    private $anyEventName;
    private $anyChannelName;
    private $anyEventData;
    private $anyPusherResponse;
    
    public function setUp()
    {   
        $this->anyEventName = 'my event';
        $this->anyChannelName=  'my channel';
        $this->anyEventData = array('awesome' => 'sauce');
        $this->anyPusherResponse = 'did it work?'; //in practice this is a bool or an array response in debug mode
        
        $this->pusherLib = $this->getMockBuilder('Pusher')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->pusher = new PusherService($this->pusherLib);
    }
    
    public function testTriggerDoesIt()
    {
        $this->pusherLib->expects($this->once())
            ->method('trigger')
            ->with($this->anyEventName, $this->anyChannelName, $this->anyEventData)
            ->will($this->returnValue($this->anyPusherResponse));
        
        $actual =  $this->pusher->trigger($this->anyEventName, $this->anyChannelName, $this->anyEventData);
        
        $this->assertEquals($this->anyPusherResponse, $actual);
    }
}