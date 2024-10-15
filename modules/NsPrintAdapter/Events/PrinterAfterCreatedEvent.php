<?php
namespace Modules\NsPrintAdapter\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\NsPrintAdapter\Models\Printer;
use Modules\NsPrintAdapter\Services\CloudPrintService;

/**
 * Register Event
**/
class PrinterAfterCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct( public Printer $printer )
    {
        // ...
    }
}