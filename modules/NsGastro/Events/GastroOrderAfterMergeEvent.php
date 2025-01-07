<?php
namespace Modules\NsGastro\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Register Event
**/
class GastroOrderAfterMergeEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct( public array $orders, public Order $order )
    {
        // ...
    }
}