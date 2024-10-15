<?php

namespace Modules\NsPrintAdapter\Jobs;

use App\Models\Order as ModelsOrder;
use App\Models\Role;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\NsGastro\Models\Order;
use Modules\NsPrintAdapter\Services\CloudPrintService;
use Modules\NsPrintAdapter\Services\PrintService;
use Throwable;

/**
 * Register Job
 **/
class KitchenCloudPrintJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct( public ModelsOrder $order, public $document = 'kitchen' )
    {
        // ...
    }

    /**
     * ...
     *
     * @return  void
     */
    public function handle(
        CloudPrintService $cloudPrintService,
        PrintService $printService,
        NotificationService $notificationService
    )
    {
        $jobs   =   $printService->getKitchenJobs( Order::find( $this->order->id ), $this->document );

        $jobs->each( function( $job ) use( $cloudPrintService ) {
            $cloudPrintService->submitPrintJob( $job );
        });
        
        if ( $jobs->count() === 0 ) {
            $notificationService->create(
                title: __m( 'Kitchen Print Failure', 'NsGastro' ),
                identifier: 'nspa-no-jobs',
                description: sprintf(
                    __m( 'No valid print jobs can be generated for the order %s. Make sure the category of the products included are assigned to existing kitchen and that valid printers are assigned"', 'NsGastro' ),
                    $this->order->code
                )
            )->dispatchForGroup([
                Role::ADMIN,
                Role::STOREADMIN
            ]);
        }
    }

    public function failed( Throwable $exception )
    {
        $notificationService    =   app()->make( NotificationService::class );

        $notificationService->create(
            title: __m( 'Kitchen Print Failure', 'NsGastro' ),
            identifier: 'nspa-no-error',
            description: $exception->getMessage()
        )->dispatchForGroup([
            Role::ADMIN,
            Role::STOREADMIN
        ]);
    }
}
