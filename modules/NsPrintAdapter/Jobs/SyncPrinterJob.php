<?php
namespace Modules\NsPrintAdapter\Jobs;

use App\Models\Role;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\NsPrintAdapter\Services\CloudPrintService;
use Throwable;

/**
 * Register Job
**/
class SyncPrinterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        // ...
    }

    /**
     * ...
     * @return void
     */
    public function handle( CloudPrintService $service )
    {
        return $service->syncPrinters();
    }

    public function failed( Throwable $exception )
    {
        /**
         * @var NotificationService $notification
         */
        $notification   =   app()->make( NotificationService::class );

        $notification->create(
            title: __( 'Unable to Sync Cloud Printers' ),
            description: __( 'Nexo Print Adapter can\'t sync your printers to the cloud. The following error was cautch: ' . $exception->getMessage() ),
            url: ns()->route( 'ns.print-adapter.printers' )
        )->dispatchForPermissions([ 'nspa.manage-printers' ]);
    }
}