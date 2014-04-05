<?php

namespace jblotus\PlanningPoker;

use Aura\Web\Request;
use GuzzleHttp\Client as PivotalService;
use Aura\Web\Response;

class Controller
{
    private $view;
    private $request;
    private $pivotal;
    private $response;
    
    public function __construct(View $view, Request $request, PivotalService $pivotal, Response $response)
    {
        $this->view = $view;
        $this->request = $request;
        $this->pivotal = $pivotal;
        $this->response = $response;
    }
    
    public function home()
    {
        $data = array();
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
}