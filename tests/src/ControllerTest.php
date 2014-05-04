<?php

namespace jblotus\PlanningPoker;

class ControllerTest extends \PHPUnit_Framework_TestCase
{      
    private $webFactory;
    private $view;
    private $authService;
    private $httpClient;
    private $controller;
    private $mockWebResponse;    
    private $response;
    private $pusher;
    
    private $anyResponseData;
    private $anyPivotalTrackerToken;
    private $anyProjectId;
    private $anyStoryId;
    
    private $anyEventName;
    private $anyChannelName;
    private $anyEventData;
    private $anySocketId;
    
    private $anyUserEmail;
    
    public function setUp()
    {
        $this->view = $this->getMockBuilder('jblotus\PlanningPoker\View')
            ->disableOriginalConstructor()
            ->setMethods(array('render'))
            ->getMock();
        
        $this->httpClient = $this->getMockBuilder('GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();
 
        $this->mockWebResponse = $this->getMockBuilder('SomeGuzzleResponse')
            ->setMethods(array('json'))
            ->getMock();
        
        $this->authService = $this->getMockBuilder('jblotus\PlanningPoker\AuthService')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->pusher = $this->getMockBuilder('jblotus\PlanningPoker\PusherService')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->anyPivotalTrackerToken = 'fooa987a98sdufahsdf';
        $this->anyProjectId = 12345;
        $this->anyStoryId = 234566;
        $this->pivotalStoryEndpoint = 'https://www.pivotaltracker.com/services/v5/projects/' . $this->anyProjectId . '/stories/' . $this->anyStoryId;
        
        $this->anyEvent = 'foo';
        $this->anyChannel = 'somechannel';
        $this->anyEventData = array('foo' => 'bar');        
        $this->anySocketId = rand();
        
        $this->anyUserEmail = 'foo@bar.com';
        
        $this->anyResponseData = array('foo');
    }
    
    public function testHomeShowsLoginLinkIfNotLoggedIn()
    {
        $this->webFactory = new \Aura\Web\WebFactory(array());
        $this->request = $this->webFactory->newRequest();
        $this->response = $this->webFactory->newResponse();
        
        $this->buildTestObj();
        
        $this->view->expects($this->once())
            ->method('render')
            ->with(
                array(
                    'showLoginLink' => true    
                ),
                VIEW_ROOT . 'home.html.php'
            )
            ->will($this->returnValue('foo'));
        
        $actual = $this->controller->home();
        $this->assertEquals('foo', $actual->content->get());
    }
    
    private function buildTestObj()
    {
        $this->controller = new Controller($this->view, $this->request, $this->authService, $this->httpClient, $this->response, $this->pusher);
    }
    
    public function testHomeDoesNotShowLoginLinkIfLoggedIn()
    {
        $this->webFactory = new \Aura\Web\WebFactory(array());
        $this->request = $this->webFactory->newRequest();
        $this->response = $this->webFactory->newResponse();
        
        $this->buildTestObj();        
        
        $this->authService->expects($this->any())
            ->method('isLoggedIn')
            ->will($this->returnValue(true));
        
        $this->view->expects($this->once())
            ->method('render')
            ->with(
                array(
                    'showLoginLink' => false    
                ),
                VIEW_ROOT . 'home.html.php'
            )
            ->will($this->returnValue('foo'));
        
        $actual = $this->controller->home();
        $this->assertEquals('foo', $actual->content->get());
    }
    
    public function testLoginRedirectsIfFreshConnection()
    {
        $this->webFactory = new \Aura\Web\WebFactory(array());
        $this->request = $this->webFactory->newRequest();
        $this->response = $this->webFactory->newResponse();
        
        $this->buildTestObj();

        $this->authService->expects($this->once())
            ->method('isFreshConnection')            
            ->will($this->returnValue(true));
        
        $this->authService->expects($this->once())
            ->method('getAuthUrl')            
            ->will($this->returnValue('http://foo.com'));
        
        $actual = $this->controller->login();
        
        $this->assertEquals(array('Location' => 'http://foo.com'), $actual->headers->get());
    }
    
    public function testLoginReturnsCorrectResponseForIfIsCancelledThirdPartyLogin()
    {
        $this->webFactory = new \Aura\Web\WebFactory(array());
        $this->request = $this->webFactory->newRequest();
        $this->response = $this->webFactory->newResponse();
        
        $this->buildTestObj();

        $this->authService->expects($this->once())
            ->method('isFreshConnection')            
            ->will($this->returnValue(false));
        
        $this->authService->expects($this->once())
            ->method('isCancelledThirdPartyLogin')            
            ->will($this->returnValue(true));
        
        $actual = $this->controller->login();
        
        $this->assertEquals("User has canceled authentication!", $actual->content->get());
    }
    
    public function testLoginLoadsUserIntoSessionAndRedirectsIfIsValidatedThirdPartyLogin()
    {
        $this->webFactory = new \Aura\Web\WebFactory(array());
        $this->request = $this->webFactory->newRequest();
        $this->response = $this->webFactory->newResponse();
        
        $this->buildTestObj();

        $this->authService->expects($this->once())
            ->method('isFreshConnection')            
            ->will($this->returnValue(false));
        
        $this->authService->expects($this->once())
            ->method('isCancelledThirdPartyLogin')            
            ->will($this->returnValue(false));        
                
        $this->authService->expects($this->once())
            ->method('isValidatedThirdPartyLogin')            
            ->will($this->returnValue(true));
        
        $this->authService->expects($this->once())
            ->method('loadUserIntoSession');
        
        $actual = $this->controller->login();
        
        $this->assertEquals(array('Location' => '/'), $actual->headers->get());
    }
    
    public function testLoginReturnsCorrectResponseForDefaultCase()
    {
        $this->webFactory = new \Aura\Web\WebFactory(array());
        $this->request = $this->webFactory->newRequest();
        $this->response = $this->webFactory->newResponse();
        
        $this->buildTestObj();

        $this->authService->expects($this->once())
            ->method('isFreshConnection')            
            ->will($this->returnValue(false));
        
        $this->authService->expects($this->once())
            ->method('isCancelledThirdPartyLogin')            
            ->will($this->returnValue(false));
        
        $this->authService->expects($this->once())
            ->method('isValidatedThirdPartyLogin')            
            ->will($this->returnValue(false));
        
        $actual = $this->controller->login();
        
        $this->assertEquals("The user has not logged in", $actual->content->get());
    }
    
    public function testGetPivotalStoryThrowsExceptionIfTokenMissing()
    {        
        $this->webFactory = new \Aura\Web\WebFactory(array());
        $this->request = $this->webFactory->newRequest();
        $this->response = $this->webFactory->newResponse();
        
        $this->buildTestObj();
        
        $this->setExpectedException('Exception', 'put PIVOTAL_TRACKER_API_TOKEN in ENV');
        
        $this->controller->getPivotalStory();
    }
    
    public function testGetPivotalStory()
    {        
        $this->webFactory = new \Aura\Web\WebFactory(array(
            '_ENV' => array(
                'PIVOTAL_TRACKER_API_TOKEN' => $this->anyPivotalTrackerToken
            ),
            '_GET' => array(
                'project_id' => $this->anyProjectId,
                'story_id' => $this->anyStoryId
            )
        ));
        $this->request = $this->webFactory->newRequest();
        $this->response = $this->webFactory->newResponse();
        
        $this->httpClient->expects($this->once())
            ->method('get')
            ->with($this->pivotalStoryEndpoint, array('headers' => array('X-TrackerToken' => $this->anyPivotalTrackerToken)))
            ->will($this->returnValue($this->mockWebResponse));
        
        $this->mockWebResponse->expects($this->once())
            ->method('json')
            ->will($this->returnValue($this->anyResponseData));
        
        $this->buildTestObj();      
        
        $actual = $this->controller->getPivotalStory();
        
        $this->assertSame($this->response, $actual);        
        $this->assertEquals(json_encode($this->anyResponseData), $this->response->content->get());
        $this->assertEquals('application/json', $this->response->content->getType());
    }
  
  public function testGetPivotalReturns500ErrorOnHttpClientException()
    {        
        $this->webFactory = new \Aura\Web\WebFactory(array(
            '_ENV' => array(
                'PIVOTAL_TRACKER_API_TOKEN' => $this->anyPivotalTrackerToken
            ),
            '_GET' => array(
                'project_id' => $this->anyProjectId,
                'story_id' => $this->anyStoryId
            )
        ));
        $this->request = $this->webFactory->newRequest();
        $this->response = $this->webFactory->newResponse();
    
        $this->httpClient->expects($this->once())
            ->method('get')
            ->will($this->throwException(new \Exception('something bad happened', 404)));
        
        $this->buildTestObj();      
        
        $actual = $this->controller->getPivotalStory();
        
        $this->assertEquals(500, $actual->status->getCode());
        $this->assertEquals('something bad happened', $actual->status->getPhrase());
        $this->assertEquals('something bad happened', $this->response->content->get()); 
    }
    
    public function testAuthorizePusherReturns403IfNotLoggedIn()
    {        
        $this->webFactory = new \Aura\Web\WebFactory(array());
        
        $this->request = $this->webFactory->newRequest();
        $this->response = $this->webFactory->newResponse();
    
        $this->authService->expects($this->once())
            ->method('isLoggedIn')
            ->will($this->returnValue(false));
        
        $this->buildTestObj();
        
        $actual = $this->controller->authorizePusher();
        
        $this->assertEquals(403, $actual->status->getCode());
    }
    
    public function testAuthorizePusherAuthorizesAndReturnsInfo()
    {        
        $this->webFactory = new \Aura\Web\WebFactory(array(
            '_POST' => array(
                'channel_name' => $this->anyChannel,
                'socket_id' => $this->anySocketId
            )
        ));
        
        $this->request = $this->webFactory->newRequest();
        $this->response = $this->webFactory->newResponse();
    
        $this->authService->expects($this->once())
            ->method('isLoggedIn')
            ->will($this->returnValue(true));
        
        
        $this->authService->expects($this->once())
            ->method('getUserEmail')
            ->will($this->returnValue($this->anyUserEmail));
        
        $this->pusher->expects($this->once())
            ->method('authorizePresence')
            ->with($this->anyChannel, $this->anySocketId, $this->anyUserEmail, array())
            ->will($this->returnValue($this->anyResponseData));
        
        $this->buildTestObj();
        
        $actual = $this->controller->authorizePusher();
        
        $this->assertEquals($this->anyResponseData, $actual->content->get());
        $this->assertEquals('application/json', $actual->content->getType());
        $this->assertEquals(200, $actual->status->getCode());
    }
    
    public function testTriggerPusherEventReturns200IfEventSent()
    {        
        $this->webFactory = new \Aura\Web\WebFactory(array(
            '_POST' => array(                
                'channel_name' => $this->anyChannelName,
                'event_name' => $this->anyEventName,
                'event_data' => $this->anyEventData
            )
        ));
        $this->request = $this->webFactory->newRequest();
        $this->response = $this->webFactory->newResponse();
    
        $this->pusher->expects($this->once())
            ->method('trigger')
            ->with($this->anyChannelName, $this->anyEventName, $this->anyEventData)
            ->will($this->returnValue(true));
        
        $this->buildTestObj();      
        
        $actual = $this->controller->triggerPusherEvent();
        
        $this->assertEquals(200, $actual->status->getCode());
    }
    
    public function testTriggerPusherEventReturns500IfEventFailed()
    {        
        $this->webFactory = new \Aura\Web\WebFactory(array(
            '_POST' => array(
                'event' => $this->anyEventName,
                'channel' => $this->anyChannelName,
                'event_data' => $this->anyEventData
            )
        ));
        $this->request = $this->webFactory->newRequest();
        $this->response = $this->webFactory->newResponse();
    
        $this->pusher->expects($this->once())
            ->method('trigger')
            ->with($this->anyEventName, $this->anyChannelName, $this->anyEventData)
            ->will($this->returnValue(false));
        
        $this->buildTestObj();      
        
        $actual = $this->controller->triggerPusherEvent();
        
        $this->assertEquals(500, $actual->status->getCode());
    }
}
