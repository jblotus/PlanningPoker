<?php

namespace jblotus\PlanningPoker;

class ViewTest extends \PHPUnit_Framework_TestCase
{ 
    private $view;
    private $viewManager;
    private $layoutTemplate;
    
    private $anyData;
    private $anyRenderedContent;
    
    public function setUp()
    {
        $this->anyData = array('foo' => 'bar');
        $this->anyRenderedContent = 'foo';
        
        $this->viewManager = $this->getMockBuilder('Aura\View\Manager')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->layoutTemplate = function() {
            return $this->anyRenderedContent;
        };
        
        $this->view = new View($this->viewManager, $this->layoutTemplate);
    }
    
    public function testInsertEstimateWithPointsDoesIt()
    {
        $viewData = array_merge($this->anyData, array('title' => 'Any title'));
        
        $this->viewManager->expects($this->once())
            ->method('render')
            ->with($viewData, function() {}, $this->layoutTemplate)
            ->will($this->returnValue($this->anyRenderedContent));
        
        $this->view->render($this->anyData, $this->anyPath);
    }
}
