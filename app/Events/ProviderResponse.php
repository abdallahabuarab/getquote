<?php
// app/Events/ProviderResponse.php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProviderResponse implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $response;
    public $requestId;
    public $reason;
    public $eta;
    public $finalPrice;

    public function __construct($response, $requestId, $reason = null, $eta = null, $finalPrice = 0)
    {
        $this->response = $response;
        $this->requestId = $requestId;
        $this->reason = $reason;
        $this->eta = $eta;
        $this->finalPrice = $finalPrice;
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
            'eta' => $this->eta,
            'finalPrice' => $this->finalPrice,
        ];
    }
}
