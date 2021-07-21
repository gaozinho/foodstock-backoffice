<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Foodstock\Babel\OrderBabelized;

class CancellationRequested
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public OrderBabelized $orderBabelized;
    public $oneBroker;
    public $reason;
    public $cancellationCode;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(OrderBabelized $orderBabelized, $oneBroker, $reason, $cancellationCode)
    {
        $this->orderBabelized = $orderBabelized;
        $this->oneBroker = $oneBroker;
        $this->reason = $reason;
        $this->cancellationCode = $cancellationCode;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
