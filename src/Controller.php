<?php

namespace jblotus\PlanningPoker;

class Controller
{
    private $view;
    
    public function __construct(View $view)
    {
        $this->view = $view;
    }
    public function home()
    {
        // data for the template
        $data = [
            'name' => 'Bolivar',
            'email' => 'boshag@example.com',
        ];         
        
        // get the rendered output, then do what you like with it
        $output = $this->view->render($data, VIEW_ROOT . 'home.html.php');
        return $output;
    }
}