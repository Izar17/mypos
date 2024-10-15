<?php

namespace Modules\NsPrintAdapter\Listeners;

use Modules\NsPrintAdapter\Events\PrinterAfterCreatedEvent;
use Modules\NsPrintAdapter\Jobs\SyncPrinterJob;
use Modules\NsPrintAdapter\Services\CloudPrintService;

class PrinterAfterCreatedEventListener
{
    /**
     * Handle the event.
     *
     * @param  object $event
     * @return  void
     */
    public function handle( PrinterAfterCreatedEvent $event )
    {
        SyncPrinterJob::dispatch();
    }
}
