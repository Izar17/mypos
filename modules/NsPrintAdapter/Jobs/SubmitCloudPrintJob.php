<?php
namespace Modules\NsPrintAdapter\Jobs;

use App\Exceptions\NotFoundException;
use App\Models\Role;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\NsPrintAdapter\Models\Printer;
use Modules\NsPrintAdapter\Services\CloudPrintService;
use Modules\NsPrintAdapter\Services\PrintService;
use Throwable;

/**
 * Register Job
**/
class SubmitCloudPrintJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Here you'll resolve your services.
     */
    public function __construct(
        public string $document,
        public $reference_id,
        public $printer_id
    )
    {
        // ...
    }

    /**
     * Here your jobs is being executed
     */
    public function handle(
        PrintService $printService,
        CloudPrintService $cloudPrintService
    )
    {
        $printers       =   Printer::enabled()->whereIn( 'id', is_array( $this->printer_id ) ? $this->printer_id : [ $this->printer_id ] )->get();

        if ( $this->printer_id === null ) {
            /** 
             * we need to load the default printers here
             */
            $printers   =   Printer::enabled()->isDefault()->get();
        }

        if ( $printers->isEmpty() ) {
            throw new NotFoundException( __( 'Unable to find the printer(s) provided. Make sure you\'ve create printers and they are enabled.' ) );
        }

        $jobs       =   $printService->getJobs( 
            document: $this->document, 
            reference_id: $this->reference_id, 
            printers: $printers 
        );

        $cloudPrintService->printJobs( $jobs );
    }

    public function failed( Throwable $exception ) 
    {
        /**
         * @var NotificationService $notificationService
         */
        $notificationService = app()->make( NotificationService::class );
        $notificationService->create(
            title: __m( 'Cloud Print Failure', 'NsPrintAdapter' ),
            description: $exception->getMessage(),
            source: 'NsPrintAdapter',
            identifier: $notificationService->generateRandomIdentifier(),
        )->dispatchForGroup([
            Role::ADMIN,
            Role::STOREADMIN
        ]);
    }
}