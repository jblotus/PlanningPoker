<?php

namespace jblotus\PlanningPoker;

class AuthServiceTest extends \PHPUnit_Framework_TestCase
{
    private $authService;
    private $session;
    private $lightOpenId;
    
    private $anySegment;
    private $anyAttributes;
    private $anyAuthUrl;
    
    public function setUp()
    {
        $this->session = $this->getMockBuilder('Aura\Session\Session')
            ->disableOriginalConstructor()
            ->setMethods(array('newSegment', 'commit'))
            ->getMock();
        
        $this->lightOpenId = $this->getMockBuilder('LightOpenID')
            ->disableOriginalConstructor()
            ->setMethods(array('validate', 'getAttributes', '__get', 'authUrl'))
            ->getMock();
        
        $this->anySegment = $this->getMockBuilder('Aura\Session\Segment')
            ->disableOriginalConstructor()
            ->getMock();
        
        $this->anyAttributes = array('contact/email' => 'foo@bar.com');
        $this->anyAuthUrl = 'http://foo.com';
        
        $this->authService = new AuthService($this->session, $this->lightOpenId);
    }
    
    public function testIsLoggedInReturnsFalseIfEmailNotInSession()
    {
        $this->session->expects($this->once())
            ->method('newSegment')
            ->with('user')
            ->will($this->returnValue($this->anySegment));
        
        $this->anySegment->expects($this->at(0))
            ->method('__get')
            ->with('email')
            ->will($this->returnValue(null));
        
        $actual = $this->authService->isLoggedIn();
        $this->assertFalse($actual);
    }
    
    public function testIsLoggedInReturnsTrueIfEmailInSession()
    {        
        $this->session->expects($this->once())
            ->method('newSegment')
            ->with('user')
            ->will($this->returnValue($this->anySegment));
        
        $this->anySegment->expects($this->at(0))
            ->method('__get')
            ->with('email')
            ->will($this->returnValue('foo@bar.com'));        
        
        $actual = $this->authService->isLoggedIn();
        $this->assertTrue($actual);
    }
    
    public function testIsFreshConnectionReturnsFalseIfModeNotEmpty()
    {
        $this->lightOpenId->expects($this->at(0))
            ->method('__get')
            ->will($this->returnValue('foo'));
        
        $actual = $this->authService->isFreshConnection();
        
        $this->assertFalse($actual);
    }
    
    public function testIsFreshConnectionReturnsTrueIfModeEmpty()
    {
        $this->lightOpenId->expects($this->at(0))
            ->method('__get')
            ->will($this->returnValue(null));
        
        $actual = $this->authService->isFreshConnection();
        
        $this->assertTrue($actual);
    }
    
    public function testIsCancelledThirdPartyLoginReturnsTrueIfModeIsCancelled()
    {
        $this->lightOpenId->expects($this->at(0))
            ->method('__get')
            ->will($this->returnValue('cancel'));
        
        $actual = $this->authService->isCancelledThirdPartyLogin();
        
        $this->assertTrue($actual);
    }
    
    public function testIsCancelledThirdPartyLoginReturnsFalseIfModeIsNull()
    {
        $this->lightOpenId->expects($this->at(0))
            ->method('__get')
            ->will($this->returnValue('foo'));
        
        $actual = $this->authService->isCancelledThirdPartyLogin();
        
        $this->assertFalse($actual);
    }
    
    public function testIsValidatedThirdPartyLoginReturnsTrueIfValidated()
    {
        $this->lightOpenId->expects($this->once())
            ->method('validate')
            ->will($this->returnValue(true));
        
        $actual = $this->authService->isValidatedThirdPartyLogin();
        
        $this->assertTrue($actual); 
    }
    
    public function testIsValidatedThirdPartyLoginReturnsTrueIfModeSetToIdRes()
    {
        $this->lightOpenId->expects($this->at(0))
            ->method('validate')
            ->will($this->returnValue(false));
        
        $this->lightOpenId->expects($this->at(1))
            ->method('__get')
            ->will($this->returnValue('id_res'));
        
        $actual = $this->authService->isValidatedThirdPartyLogin();
        
        $this->assertTrue($actual); 
    }    
        
    public function testIsValidatedThirdPartyLoginReturnsFalseByDefault()
    {
        $this->lightOpenId->expects($this->at(0))
            ->method('validate')
            ->will($this->returnValue(false));
        
        $this->lightOpenId->expects($this->at(1))
            ->method('__get')
            ->will($this->returnValue(null));
        
        $actual = $this->authService->isValidatedThirdPartyLogin();
        
        $this->assertFalse($actual); 
    }
    
    public function testLoadUserIntoSessionDoesIt()
    {
        $this->lightOpenId->expects($this->once())
            ->method('getAttributes')
            ->will($this->returnValue($this->anyAttributes));
                
        $this->session->expects($this->once())
            ->method('newSegment')
            ->with('user')
            ->will($this->returnValue($this->anySegment));
        
        $this->anySegment->expects($this->at(0))
            ->method('__set')
            ->with('email', $this->anyAttributes['contact/email']);
        
        $this->session->expects($this->once())
            ->method('commit');
        
        $this->authService->loadUserIntoSession();
    }
    
    public function testGetAuthUrlDoesIt()
    {
        $this->lightOpenId->expects($this->once())
            ->method('authUrl')
            ->will($this->returnValue($this->anyAuthUrl));
        $actual = $this->authService->getAuthUrl();
        $this->assertEquals($this->anyAuthUrl, $actual);
    }
}