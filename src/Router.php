<?php

namespace jblotus\PlanningPoker;

use Aura\Router\Router as AuraRouter;

class Router
{
    private $router;
    
    public function __construct(AuraRouter $router)
    {
        $this->router = $router;
    }
    public function initialize()
    {                
        $this->router->add('home', "/")
            ->addValues(array(
                'controller'=> function(array $params) {
                    echo 'foo';
                }
            ));
        
        // get the incoming request URL path
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // get the route based on the path and server
        $route = $this->router->match($path, $_SERVER);
        
        if (!$route) {
            // no route object was returned
            echo "No application route was found for that URL path.";
            exit();
        }
        // get the route params
        $params = $route->params;
        
        // extract the controller callable from the params
        $controller = $params['controller'];
        unset($params['controller']);
        
        // invoke the callable
        $controller($params);
    }
    
}