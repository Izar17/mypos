<?php
namespace Modules\NsGastro\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\NsGastro\Models\Table;

/**
 * Register Event
**/
class GastroOrderAfterMovedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct( public Table $previousTable, public Table $newTable, public Order $order )
    {
        // ...
    }
}