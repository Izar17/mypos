<?php

namespace Modules\NsPrintAdapter\Services;

use App\Exceptions\NotFoundException;
use App\Models\Order;
use App\Models\Register;
use App\Models\Role;
use App\Services\NotificationService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Modules\NsPrintAdapter\Models\Printer;
use Modules\NsGastro\Models\KitchenPrinter;
use Modules\NsPrintAdapter\Events\AfterSubmitCloudPrintJobEvent;
use Modules\NsPrintAdapter\Events\BeforeSubmitCloudPrintJobEvent;
use Modules\NsPrintAdapter\Libraries\PrintJob;

class CloudPrintService
{
    protected $domain;

    public function __construct(
        public NotificationService $notificationService,
    )
    {
        $this->domain = env('MNS_DOMAIN', 'https://my.nexopos.com');
    }

    public function submitPrintJob( PrintJob $printJob ): array
    {
        BeforeSubmitCloudPrintJobEvent::dispatch( $printJob );

        $domain = env('MNS_DOMAIN', 'https://my.nexopos.com');
        $response = Http::withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '.ns()->option->get('ns_pa_access_token'),
        ])->post($domain.'/api/user/printers/submit-job', [
            'job'                   =>  $printJob->content,
            'printerIdentifier'     =>  $printJob->printer->identifier,
        ]);

        if ($response->status() !== 200) {
            throw new Exception($response->object()->message);
        }

        AfterSubmitCloudPrintJobEvent::dispatch( $printJob, $response->json() );

        return $response->json();
    }

    public function syncPrinters( $direction = 'up' )
    {
        if ( $direction === 'up' ) {
            $response = Http::withHeaders([
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer '.ns()->option->get('ns_pa_access_token'),
            ])->post($this->domain.'/api/user/sync-printers', [
                'printers'  =>  Printer::enabled()->get(),
                'setup'     =>  ns()->option->get('ns_pa_setup_hash'),
            ]);
    
            if ($response->status() !== 200) {
                throw new Exception($response->object()->message);
            }
    
            return $response->json();
        } else if ( $direction === 'down' ) {
            Printer::truncate();

            $response = Http::withHeaders([
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer ' . ns()->option->get('ns_pa_access_token'),
            ])->get( $this->domain . '/api/user/setups/' . ns()->option->get('ns_pa_setup_hash') . '/printers' );

            foreach( $response->json() as $printerData ) {
                $printer                =   Printer::where( 'name', $printerData[ 'name' ] )->firstOrNew();
                $printer->name          =   $printerData[ 'name' ];
                $printer->interface     =   $printerData[ 'interface' ];
                $printer->identifier    =   $printerData[ 'identifier' ];
                $printer->characterset  =   $printerData[ 'characterset' ];
                $printer->type          =   'epson'; // by default
                $printer->author        =   Auth::id();
                $printer->saveQuietly();
            }
    
            if ($response->status() !== 200) {
                throw new Exception($response->object()->message);
            }
    
            return $response->json();
        }
    }

    public function printJobs( $jobs )
    {
        return $jobs->map( fn( $job ) => $this->submitPrintJob( $job ) );
    } 

    /**
     * Will trigger print for a specific
     * template.
     */
    public function printTemplate( string $content, Printer $printer )
    {
        $job    =   new PrintJob(
            printer: $printer,
            content: $content
        );
        
        return $this->submitPrintJob( $job );
    }

    public function printKitchenCloudReceipts( $receipts, $order )
    {
        if( $receipts->isEmpty() ) {
            $this->notificationService->create([
                'title' =>  __m( 'Cloud Print Failure', 'NsPrintAdapter' ),
                'identifier'    =>  'nspa.gastro-empty-receipts',
                'url'  =>  'https://my.nexopos.com/en/documentation/troubleshooting/gastro-empty-receipts',
                'description' => sprintf( 
                    __m( 'The kitchen printing failed for "%s", because there is no receipt to print. This might be caused by a misconfiguration of the split printing on the kitchen.' ),
                    $order->code
                )
            ])->dispatchForGroup([ Role::ADMIN ]);
        } else {
            $receipts->each(function ($receipt) {
                /**
                 * @param  Kitchen  $kitchen
                 * @param  Collection  $products
                 * @param  string  $nps_address
                 * @param  array  $printers
                 */
                extract($receipt);

                $kitchenPrinters = KitchenPrinter::where( 'kitchen_id', $kitchen->id )->with( 'printer' )->get();
    
                $kitchenPrinters->each(function ( KitchenPrinter $kitchenPrinter) use ( $template ) {
                    $printJob   =   new PrintJob(
                        printer: $kitchenPrinter->printer,
                        content: $template
                    );
                    
                    $this->submitPrintJob( $printJob );
                });
            });
        }       
    }

    public function getDefinedPrinter(Order $order)
    {
        /**
         * We'll use the cash register printer
         */
        $register = Register::find($order->register_id);

        if ($register instanceof Register) {
            return Printer::find($register->cloud_printer_id);
        }

        /**
         * If it's not provided, we'll use the default printer
         */
        $printer_id = ns()->option->get('ns_pa_cloud_printer_id');

        return Printer::find($printer_id);
    }
}
