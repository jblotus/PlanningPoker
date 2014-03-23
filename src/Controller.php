<?php

namespace jblotus\PlanningPoker;

use Aura\Web\Request;

class Controller
{
    private $view;
    private $request;
    
    public function __construct(View $view, Request $request)
    {
        $this->view = $view;
        $this->request = $request;
    }
    
    public function home()
    {
        $data = array();
        // get the rendered output, then do what you like with it
        $output = $this->view->render($data, VIEW_ROOT . 'home.html.php');
        return $output;
    }
    
    public function getPivotalStory()
    {
        
    }
}