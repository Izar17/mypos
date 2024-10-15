<?php
namespace Modules\NsPrintAdapter\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\NsPrintAdapter\Libraries\PrintJob;

/**
 * Register Event
**/
class AfterSubmitCloudPrintJobEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct( public PrintJob $printJob, public array $response )
    {
        // ...
    }
}