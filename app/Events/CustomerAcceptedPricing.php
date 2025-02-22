<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CustomerAcceptedPricing implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $requestId;

    public function __construct($requestId)
    {
        $this->requestId = $requestId;
    }

    public function broadcastOn()
    {
        return new Channel('request.' . $this->requestId);
    }

    public function broadcastWith()
    {
        return [
            'requestId' => $this->requestId
        ];
    }
}
