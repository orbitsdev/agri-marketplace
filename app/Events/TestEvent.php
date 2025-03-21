<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;



    /**
     * Create a new event instance.
     */

     public $user;
     public $message;
    public function __construct($user) {
        $this->user = $user;
        $this->message = 'User has been created yoews';
    }

    public function broadcastWith(): array
    {
        return ['id' => $this->user->id, 'message' => $this->message];
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('test'), // ✅ Public channel for testing
        ];
    }

    public function broadcastAs()
    {
        return 'TestEventReceived'; // ✅ Custom event name
    }
}
