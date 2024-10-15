<?php
namespace Modules\NsPrintAdapter\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Register Event
**/
class CloudPrintAfterDispatchedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct( public $document, public $reference_id, public $printer_id )
    {
        // ...
    }
}