<?php
namespace Modules\NsPrintAdapter\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Register Event
**/
class AfterPrintJobsCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct( public array $printjobs )
    {
        // ...
    }
}