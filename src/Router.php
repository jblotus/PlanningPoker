<?php

namespace jblotus\PlanningPoker;

use Aura\Router\Router as AuraRouter;
use Aura\Web\Request as Request;
use Aura\Web\Response as Response;

class Router
{
    private $router;
    private $request;
    private $response;
    
    public function __construct(AuraRouter $router, Request $request, Response $response)
    {
        $this->router = $router;
        $this->request = $request;
        $this->response = $response;
    }
    public function initialize()
    {      
        $this->defineRoutes();        
        return $this->getCurrentRoute();
    }
    
    private function defineRoutes()
    {
        $this->router
            ->add('home', "/");
    }
    
    private function getCurrentRoute()
    {
        // get the incoming request URL path
        $path = parse_url($this->request->server->get('REQUEST_URI'), PHP_URL_PATH);
        
        // get the route based on the path and server
        $route = $this->router->match($path, $this->request->server->get());
        
        return $route;
    }
    
}