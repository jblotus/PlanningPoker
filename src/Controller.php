<?php

namespace jblotus\PlanningPoker;

use Aura\Web\Request;
use GuzzleHttp\Client as PivotalService;
use Aura\Web\Response;
use Aura\Session\Session;
use LightOpenID;

class Controller
{
    private $view;
    private $request;
    private $session;
    private $pivotal;
    private $response;
    
    public function __construct(View $view, Request $request, Session $session, PivotalService $pivotal, Response $response)
    {
        $this->view = $view;
        $this->request = $request;
        $this->session = $session;
        $this->pivotal = $pivotal;
        $this->response = $response;
    }  
    
    public function home()
    {
        $this->authenticate();

        $isLoggedIn = $this->isLoggedIn();
      
        $data = array('showLoginLink' => !$isLoggedIn);

        // get the rendered output, then do what you like with it
        $output = $this->view->render($data, VIEW_ROOT . 'home.html.php');
        
        $this->response->content->set($output);
        
        return $this->response;
    }
    
    public function getPivotalStory()
    {        
        $token = $this->request->env->get('PIVOTAL_TRACKER_API_TOKEN');    
    
        if (!$token) {
            throw new \Exception('put PIVOTAL_TRACKER_API_TOKEN in ENV');
        }    
        
        $projectId = $this->request->query->get('project_id');
        $storyId = $this->request->query->get('story_id');
        $endpoint = sprintf('https://www.pivotaltracker.com/services/v5/projects/%s/stories/%s', $projectId, $storyId );
         
        $response = $this->pivotal->get($endpoint, array(
            'headers' => array('X-TrackerToken' => $token)
        ));

        $this->response->content->setType('application/json');
        $json = json_encode($response->json());
        $this->response->content->set($json);
        return $this->response;
    }
    
    public function login()
    {
        //refactor this out before commit
        $openid = new LightOpenId('planning-poker-91022.use1.nitrousbox.com:4000');

        $openid->identity = 'https://www.google.com/accounts/o8/id';
        $openid->required = array(
          'namePerson/first',
          'namePerson/last',
          'contact/email',
        );         
        
        if (!$openid->mode) {            
            $this->response->redirect->to($openid->authUrl());
            return $this->response; 
        }     
        
        if ($openid->mode == 'cancel') {            
            echo "User has canceled authentication!";
        } else if($openid->validate() || $openid->mode === 'id_res') {

            $data = $openid->getAttributes();            
            $segment = $this->getUserSession();
            $segment->email = $data['contact/email'];
            $this->session->commit(); 
            
            $this->response->redirect->to('/');
            return $this->response;
        }
        
        $this->response->content->set("The user has not logged in");
        return $this->response;                
    }    
    
    private function authenticate()
    {
        //debug
        //$this->session->destroy();        

        $segment = $this->getUserSession();        
        if (!$this->isLoggedIn()) {               
            $this->response->redirect->to('/login');            
            return $this->response;
        }
    }
    
    private function isLoggedIn()
    {
        $segment = $this->getUserSession();
        return !!$segment->email;
    }
    
    private function getUserSession()
    {
        return $this->session->newSegment('user');
    }
}