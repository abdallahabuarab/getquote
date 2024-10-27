<?php

namespace App\Events;

use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CustomerFormFilled implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $success;
    public $requestId;
    public $canceled;

    public function __construct($success, $requestId, $canceled = false)
    {
        $this->success = $success;
        $this->requestId = $requestId;
        $this->canceled = $canceled;

        Log::info("CustomerSubmitted Event Created: success={$this->success}, canceled={$this->canceled}, requestId={$this->requestId}");
    }

    public function broadcastOn()
    {
        return new Channel('request.' . $this->requestId);
    }

    public function broadcastWith()
    {
        Log::info("Broadcasting with data: success={$this->success}, canceled={$this->canceled}, requestId={$this->requestId}");
        return [
            'success' => $this->success,
            'canceled' => $this->canceled,
            'requestId' => $this->requestId,
        ];
    }
}
