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
    
    public function authorizePresence($channel, $socketId, $userId, array $customData)
    {
        return $this->pusher->presence_auth($channel, $socketId, $userId, $customData);
    }
}