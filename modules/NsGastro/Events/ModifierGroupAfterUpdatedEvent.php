<?php
namespace Modules\NsGastro\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\NsGastro\Models\ModifierGroup;

/**
 * Register Event
**/
class ModifierGroupAfterUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct( public ModifierGroup $modifierGroup )
    {
        // ...
    }
}