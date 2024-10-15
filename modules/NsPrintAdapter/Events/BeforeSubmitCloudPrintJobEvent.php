<?php
namespace Modules\NsPrintAdapter\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\NsPrintAdapter\Libraries\PrintJob;

/**
 * Register Event
**/
class BeforeSubmitCloudPrintJobEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct( public PrintJob $printJob )
    {
        // ...
    }
}