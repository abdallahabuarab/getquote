<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CustomerRejectedPricing implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $requestId;
    public $reason;

    public function __construct($requestId, $reason)
    {
        $this->requestId = $requestId;
        $this->reason = $reason;
    }

    public function broadcastOn()
    {
        return new Channel('request.' . $this->requestId);
    }

    public function broadcastWith()
    {
        return [
            'requestId' => $this->requestId,
            'reason' => $this->reason
        ];
    }
}
