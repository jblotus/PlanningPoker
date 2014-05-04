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

        $_SERVER['REQUEST_URI'] = 'http://test.com/backend/foo';
            
        $webFactory = new \Aura\Web\WebFactory($GLOBALS);
        
        $this->request = $webFactory->newRequest();        
        $this->response = $webFactory->newResponse();
        
        $this->router = new Router($this->internalRouter, $this->request, $this->response);
    }
    
    public function testInitializeDoesIt()
    {   
        $this->internalRouter->expects($this->at(0))
            ->method('add')
            ->with('getPivotalStory', "/backend/get_pivotal_story");
        
        $this->internalRouter->expects($this->at(1))
            ->method('add')
            ->with('login', "/backend/login");
        
        $this->internalRouter->expects($this->at(2))
            ->method('add')
            ->with('triggerPusherEvent', "/backend/pusher");
      
        $this->internalRouter->expects($this->at(3))
            ->method('add')
            ->with('authorizePusher', "/backend/authpusher");
      
        $mockHomeRoute = $this->getMockBuilder('Aura\Router\Route')
            ->disableOriginalConstructor()
            ->setMethods(array('addTokens'))
            ->getMock();
        
        $mockHomeRoute->expects($this->once())
            ->method('addTokens')
            ->with(array(
                'any' => '\/(?!backend).+|\/'
            ));
        
        $this->internalRouter->expects($this->at(4))
            ->method('add')
            ->with('home', "{any}")
            ->will($this->returnValue($mockHomeRoute));
        
        $this->internalRouter->expects($this->atLeastOnce())
            ->method('match')
            ->with('/backend/foo', $this->request->server->get());
        
        $actual = $this->router->initialize();         
    }    
}
