<?php
namespace Modules\NsPrintAdapter\Jobs;

use App\Models\Order;
use App\Models\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\NsPrintAdapter\Services\CloudPrintService;

/**
 * Register Job
**/
class PrintOrderReceiptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct( public Order $order )
    {
        // ...
    }

    /**
     * ...
     * @return  void
     */
    public function handle( CloudPrintService $cloudPrintService )
    {
        if ( ns()->option->get( 'ns_pa_cloud_print', 'no' ) === 'yes' ) {
            $cloudPrintService->printOrder( $this->order );
        }
    }
}