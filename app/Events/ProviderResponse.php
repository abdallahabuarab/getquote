<?php
// app/Events/ProviderResponse.php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProviderResponse implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $response;
    public $requestId;
    public $reason;

    public function __construct($response, $requestId, $reason = null)
    {
        $this->response = $response;
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
            'response' => $this->response,
            'requestId' => $this->requestId,
            'reason' => $this->reason,
        ];
    }
}
