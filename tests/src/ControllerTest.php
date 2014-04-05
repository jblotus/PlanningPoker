<?php

namespace jblotus\PlanningPoker;

class ControllerTest extends \PHPUnit_Framework_TestCase
{      
    private $webFactory;
    private $view;
    private $httpClient;
    private $controller;
    private $mockWebResponse;
    private $response;
    
    private $anyResponseData;
    private $anyPivotalTrackerToken;
    private $anyProjectId;
    private $anyStoryId;
    
    public function setUp()
    {
        $this->view = $this->getMockBuilder('jblotus\PlanningPoker\View')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();
        
        $this->httpClient = $this->getMockBuilder('GuzzleHttp\Client')
            ->disableOriginalConstructor()
            ->setMethods(array())
            ->getMock();
 
        $this->mockWebResponse = $this->getMockBuilder('SomeGuzzleResponse')
            ->setMethods(array('json'))
            ->getMock();
        
        $this->anyPivotalTrackerToken = 'fooa987a98sdufahsdf';
        $this->anyProjectId = 12345;
        $this->anyStoryId = 234566;
        $this->pivotalStoryEndpoint = 'https://www.pivotaltracker.com/services/v5/projects/' . $this->anyProjectId . '/stories/' . $this->anyStoryId;
        
        $this->anyResponseData = array('foo');
    }
    
    public function testGetPivotalStoryThrowsExceptionIfTokenMissing()
    {        
        $this->webFactory = new \Aura\Web\WebFactory(array());
        $this->request = $this->webFactory->newRequest();
        $this->response = $this->webFactory->newResponse();
        
        $this->controller = new Controller($this->view, $this->request, $this->httpClient, $this->response);
        
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
        
        $this->controller = new Controller($this->view, $this->request, $this->httpClient, $this->response);
         
        
        $actual = $this->controller->getPivotalStory();
        
        $this->assertSame($this->response, $actual);        
        $this->assertEquals(json_encode($this->anyResponseData), $this->response->content->get());
        $this->assertEquals('application/json', $this->response->content->getType());
    }
}
