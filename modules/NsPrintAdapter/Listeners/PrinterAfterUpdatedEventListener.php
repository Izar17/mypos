<?php

namespace Modules\NsPrintAdapter\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\NsPrintAdapter\Events\PrinterAfterUpdatedEvent;
use Modules\NsPrintAdapter\Jobs\SyncPrinterJob;
use Modules\NsPrintAdapter\Services\CloudPrintService;

class PrinterAfterUpdatedEventListener
{
    /**
     * Handle the event.
     *
     * @param  object $event
     * @return  void
     */
    public function handle( PrinterAfterUpdatedEvent $event )
    {
        SyncPrinterJob::dispatch();
    }
}
