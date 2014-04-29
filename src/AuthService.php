<?php

namespace jblotus\PlanningPoker;

use Aura\Session\Session;
use LightOpenID;

class AuthService
{  
    private $session;
    private $lightOpenId;
  
    public function __construct(Session $session, LightOpenId $lightOpenId)
    {
        $this->session = $session;
        $this->lightOpenId = $lightOpenId;
    }
  
    public function isLoggedIn()
    {
        $segment = $this->getUserSession();
        return !!$segment->email;
    }
    
    public function isFreshConnection()
    {   
        return !$this->lightOpenId->mode;
    }
    
    public function isCancelledThirdPartyLogin()
    {
        return $this->lightOpenId->mode === 'cancel';
    }
    
    public function isValidatedThirdPartyLogin()
    {
        return $this->lightOpenId->validate() || $this->lightOpenId->mode === 'id_res';
    }
    
    public function loadUserIntoSession()
    {        
        $data = $this->lightOpenId->getAttributes();            
        $segment = $this->getUserSession();
        $segment->email = $data['contact/email'];
        $this->session->commit(); 
    }
    
    public function getAuthUrl()
    {
        return $this->lightOpenId->authUrl();
    }
  
    private function getUserSession()
    {
        return $this->session->newSegment('user');
    }
}