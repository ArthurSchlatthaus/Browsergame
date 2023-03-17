<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class sendInfo implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;
    public $api_token;

    public function __construct($user, $message)
    {
        $this->message = $message;
        $this->user = $user;
        $this->api_token = $user->api_token;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('infoChannel.' . $this->user->api_token);
    }

    public function broadcastAs()
    {
        return 'sendInfo';
    }

    public function broadcastWith()
    {
        return [
            'data' => $this->message
        ];
    }
}
