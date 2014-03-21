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
        
        // the view template
        $view_template = function () {
            echo '<p>Hello ' . $this->safeHtml($this->name) . '. '
               . 'Your email address is ' . $this->safeHtml($this->email) . '.</p>';
        };
        
        // get the rendered output, then do what you like with it
        $output = $this->view->render($data, $view_template);
        return $output;
    }
}