<?php

namespace jblotus\PlanningPoker;

class RouterTest extends \PHPUnit_Framework_TestCase
{ 
    private $internalRouter;
    private $request;
    private $response;
    private $router;
    
    public function setUp()
    {
        $this->internalRouter = $this->getMockBuilder('Aura\Router\Router')
            ->disableOriginalConstructor()
            ->setMethods(array('add', 'match'))
            ->getMock();

        $_SERVER['REQUEST_URI'] = 'http://test.com/foo';
            
        $webFactory = new \Aura\Web\WebFactory($GLOBALS);
        
        $this->request = $webFactory->newRequest();        
        $this->response = $webFactory->newResponse();
        
        $this->router = new Router($this->internalRouter, $this->request, $this->response);
    }
    
    public function testInitializeDoesIt()
    { 
        $this->internalRouter->expects($this->at(0))
            ->method('add')
            ->with('home', "/");
        
        $this->internalRouter->expects($this->at(1))
            ->method('add')
            ->with('getPivotalStory', "/get_pivotal_story");
        
        $this->internalRouter->expects($this->at(2))
            ->method('add')
            ->with('login', "/login");
        
        $this->internalRouter->expects($this->at(3))
            ->method('add')
            ->with('triggerPusherEvent', "/pusher");
        
        $this->internalRouter->expects($this->atLeastOnce())
            ->method('match')
            ->with('/foo', $this->request->server->get());
        
        $actual = $this->router->initialize();         
    }    
}
