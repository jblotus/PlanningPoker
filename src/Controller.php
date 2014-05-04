<?php

namespace jblotus\PlanningPoker;

use Aura\Web\Request;
use GuzzleHttp\Client as PivotalService;
use Aura\Web\Response;
use Exception;

class Controller
{
    private $view;
    private $request;
    private $authService;
    private $pivotal;
    private $response;
    private $pusher;
    
    public function __construct(View $view, Request $request, AuthService $authService, PivotalService $pivotal, Response $response, PusherService $pusher)
    {
        $this->view = $view;
        $this->request = $request;
        $this->authService = $authService;
        $this->pivotal = $pivotal;
        $this->response = $response;
        $this->pusher = $pusher;
    }  
    
    public function home()
    {
        $isLoggedIn = $this->authService->isLoggedIn();
      
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
        
         
        try {
            $endpoint = sprintf('https://www.pivotaltracker.com/services/v5/projects/%s/stories/%s', $projectId, $storyId );
            $response = $this->pivotal->get($endpoint, array(
                'headers' => array('X-TrackerToken' => $token)
            ));
                        
            $json = json_encode($response->json());
            $this->response->content->setType('application/json');
            
            $this->response->content->set($json);
            
        } catch (Exception $ex) {
            $this->response->status->setCode(500);
            $this->response->status->setPhrase($ex->getMessage());
            $this->response->content->set($ex->getMessage());
        }
        
        return $this->response;
    }
    
    public function login()
    {
        if ($this->authService->isFreshConnection()) {
            $this->response->redirect->to($this->authService->getAuthUrl());
            return $this->response; 
        }
        
        if ($this->authService->isCancelledThirdPartyLogin()) {                        
            $this->response->content->set("User has canceled authentication!");
            return $this->response; 
        } 
        
        if ($this->authService->isValidatedThirdPartyLogin()) {
            $this->authService->loadUserIntoSession();            
            $this->response->redirect->to('/');
            return $this->response;
        }
        
        $this->response->content->set("The user has not logged in");
        return $this->response;                
    }
    
    public function triggerPusherEvent()
    {
        $post = $this->request->post;
        $channel = $post->get('channel');
        $event = $post->get('event');
        $eventData = $post->get('event_data');
        $results = $this->pusher->trigger($channel, $event, $eventData);
        
        if ($results) {
            $this->response->status->setCode('200');
        } else {
            $this->response->status->setCode('500');
        }
        
        return $this->response;
    }
    
    private function authenticate()
    {
        if (!$this->authService->isLoggedIn()) {
            $this->response->redirect->to('/login');            
            return $this->response;
        }
    } 
}