<?php

namespace jblotus\PlanningPoker;

use Pusher;

class PusherService
{  
    private $pusher;
  
    public function __construct(Pusher $pusher)
    {
        $this->pusher = $pusher;
    }
    
    public function trigger($channel, $event, $data)
    {
        return $this->pusher->trigger($channel, $event, $data);
    }
}