<?php

namespace jblotus\PlanningPoker;

use Aura\View\Manager as ViewManager;

class View
{
    private $viewManager;
    
    private $layoutTemplate;
    
    public function __construct(ViewManager $viewManager, callable $layoutTemplate)
    {
        $this->viewManager = $viewManager;        
        $this->layoutTemplate = $layoutTemplate;
    }
    
    public function render(array $data, $path)
    {
        $data['title'] = 'Any title';
        $template = function() use ($path) {
            require_once $path;   
        };
        
        return $this->viewManager->render($data, $template, $this->layoutTemplate);
    }
}