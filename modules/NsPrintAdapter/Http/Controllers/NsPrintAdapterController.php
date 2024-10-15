<?php

namespace Modules\NsPrintAdapter\Http\Controllers;

use App\Classes\Hook;
use App\Classes\Output;
use App\Exceptions\NotAllowedException;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\DashboardController;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderRefund;
use App\Models\PaymentType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Modules\NsGastro\Models\Order as GastroOrder;
use Modules\NsPrintAdapter\Crud\PrinterCrud;
use Modules\NsPrintAdapter\Http\Requests\PrinterSetupRequest;
use Modules\NsPrintAdapter\Jobs\SubmitCloudPrintJob;
use Modules\NsPrintAdapter\Models\Printer;
use Modules\NsPrintAdapter\Services\CloudPrintService;
use Modules\NsPrintAdapter\Services\PrintService;
use Modules\NsPrintAdapter\Settings\PrintAdapterSettings;

class NsPrintAdapterController extends DashboardController
{
    public string $domain;
    
    public function __construct(
        protected CloudPrintService $cloudPrintService,
        protected PrintService $printService
    )
    {
        $this->domain = env('MNS_DOMAIN', 'https://my.nexopos.com');
    }

    public function getSettingsPage()
    {
        Hook::addAction('ns-dashboard-footer', function (Output $output) {
            $output->addView('NsPrintAdapter::footer');
        });

        return PrintAdapterSettings::renderForm();
    }

    public function getKitchenReceipt( Request $request )
    {
        $reference_id   =   $request->input( 'reference_id' );
        $document       =   $request->input( 'document' );

        return $this->printService->getKitchenJobs( 
            document: $document, 
            order: GastroOrder::find( $reference_id ),
        );
    }

    public function getReceipt( Request $request )
    {
        $printer_id     =   $request->input( 'printer' );
        $reference_id   =   $request->input( 'reference_id' );
        $printers       =   Printer::enabled()->whereIn( 'id', is_array( $printer_id ) ? $printer_id : [ $printer_id ] )->get();
        $document       =   $request->input( 'document' );

        if ( $printer_id === null ) {
            /** 
             * we need to load the default printers here
             */
            $printers   =   Printer::enabled()->isDefault()->get();
        }

        if ( $printers->isEmpty() ) {
            throw new NotFoundException( __( 'Unable to find the printer(s) provided. Make sure you\'ve create printers and they are enabled.' ) );
        }

        return $this->printService->getJobs( 
            document: $document, 
            reference_id: $reference_id, 
            printers: $printers 
        );
    }

    /**
     * Will print the receipt on the cloud
     *
     * @param Request $request
     */
    public function printOnCloud( Request $request ): array
    {
        $printer_id     =   $request->input( 'printer' );
        $reference_id   =   $request->input( 'reference_id' );
        $document       =   $request->input( 'document' );
        
        SubmitCloudPrintJob::dispatch( $document, $reference_id, $printer_id );

        return [
            'status'    =>  'success',
            'message'   =>  __m( 'The print job has been submitted.', 'NsPrintAdapter' ),
        ];
    }

    public function getPrinters()
    {
        return PrinterCrud::table();
    }

    public function createPrinter()
    {
        return PrinterCrud::form();
    }

    public function editPrinter(Printer $printer)
    {
        return PrinterCrud::form($printer);
    }

    public function getAuthentication(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id' =>  ns()->option->get('ns_pa_app_id'),
            'redirect_uri' => ns()->route('ns.print-adapter.authenticate-callback'),
            'response_type' => 'code',
            'scope' => 'manage-printers',
            'state' => $state,
        ]);

        return redirect($this->domain.'/oauth/authorize?'.$query);
    }

    public function authenticateCallback(Request $request)
    {
        $state = $request->session()->pull('state');

        throw_unless(
            strlen($state) > 0 && $state === $request->state,
            InvalidArgumentException::class
        );

        $response = Http::asForm()->post($this->domain.'/oauth/token', [
            'grant_type'        => 'authorization_code',
            'client_id'         => ns()->option->get('ns_pa_app_id'),
            'client_secret'     => ns()->option->get('ns_pa_secret_key'),
            'redirect_uri'      => ns()->route('ns.print-adapter.authenticate-callback'),
            'code'              => $request->code,
        ]);

        $result = $response->object();

        ns()->option->set('ns_pa_access_token', $result->access_token);
        ns()->option->set('ns_pa_refresh_token', $result->refresh_token);

        return redirect(ns()->route('ns.print-adapter.settings'));
    }

    public function unlink()
    {
        $response = Http::withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '.ns()->option->get('ns_pa_access_token'),
        ])->post($this->domain.'/api/user/revoke-access-token', [
            'access_token'  =>  ns()->option->get('ns_pa_access_token'),
        ]);

        ns()->option->delete('ns_pa_access_token');
        ns()->option->delete('ns_pa_refresh_token');

        if ($response->failed()) {
            $result = $response->object();

            if ($result->message === 'Unauthenticated') {
                throw new NotAllowedException(__m('Unable to delete the link with the platform, as no connexion seems existing. Please go back and try again.', 'NsPrintAdapter'));
            }
        }

        return redirect(ns()->route('ns.print-adapter.settings') . '?tab=cloud' )->with(
            'message', __m('The access token has been deleted.', 'NsPrintAdapter')
        );
    }

    public function syncPrinters( $direction = 'down' )
    {
        /**
         * @var CloudPrintService
         */
        $service    =   app()->make( CloudPrintService::class );
        return $service->syncPrinters( $direction );
    }

    public function getSetups()
    {
        $response = Http::withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '.ns()->option->get('ns_pa_access_token'),
        ])->get($this->domain.'/api/user/setups');

        return collect($response->object())->map(function ($setup) {
            return [
                'label' =>  $setup->name,
                'value' =>  $setup->hash,
            ];
        });
    }

    public function saveSetup(PrinterSetupRequest $request)
    {
        ns()->option->set('ns_pa_setup_name', $request->input('setup_name'));
        ns()->option->set('ns_pa_setup_hash', $request->input('setup_id'));

        return [
            'status'    =>  'success',
            'message'   =>  __m('The setup hash has been saved.', 'NsPrintAdapter'),
        ];
    }

    public function saveImage( Request $request )
    {
        $image          =   $request->input( 'image' );
        $imageName      =   'nps-image-' . Str::random(20) . '.png';
        $imagePath      =   storage_path( 'app/public/' . $imageName );
        $finalImage     =   $this->printService->base64ToPng( $image, storage_path( 'app/public/' . $imagePath ) );
        
        Storage::disk( 'public' )->put( $imageName, $finalImage );

        return [
            'status'    =>  'success',
            'message'   =>  __m( 'The image was saved.', 'NsPrintAdapter' ),
            'data'      =>  [
                'url'   =>  Storage::disk( 'public' )->url( $imageName )
            ]
        ];
    }

    /**
     * Deletes the actual setup hash to force
     * selecting another setup hash
     *
     * @return Redirection
     */
    public function deleteSetup()
    {
        ns()->option->delete('ns_pa_setup_hash');
        ns()->option->delete('ns_pa_setup_name' );

        return redirect(url()->previous())->with('message', __('No printer setup is assigned to the installation.'));
    }

    /**
     * Will return all the enabled printers
     *
     * @return array $printers
     */
    public function getEnabledPrinters()
    {
        return Printer::enabled()->get();
    }

    /**
     * Saves the printer settings
     *
     * @param  Request  $request
     * @return void
     */
    public function saveSettings(Request $request)
    {
        $fields = $request->all();

        foreach ($fields as $key => $value) {
            /**
             * @todo we might restrict the options that can be saved here
             */
            ns()->option->set($key, $value);
        }

        return [
            'status'    =>  'success',
            'message'   =>  __m('The settings has been saved.', 'NsPrintAdapter'),
        ];
    }

    public function refreshPrinters( Request $request )
    {
        return $this->printService->refreshPrinters( $request->input( 'printers' ) );
    }

    public function getSettingsFields()
    {        
        return [
            [
                'type'          =>  'text',
                'label'         =>  __m('App ID', 'NsPrintAdapter'),
                'value'         =>  ns()->option->get('ns_pa_app_id'),
                'description'   =>  __m('Provide the App ID for authentication on my.nexopos.com', 'NsPrintAdapter'),
                'name'          =>  'ns_pa_app_id',
            ], [
                'type'          =>  'password',
                'label'         =>  __m('Secret Key', 'NsPrintAdapter'),
                'value'         =>  ns()->option->get('ns_pa_secret_key'),
                'description'   =>  __m('Provide the secret key ', 'NsPrintAdapter'),
                'name'          =>  'ns_pa_secret_key',
            ],
        ];
    }

    public function saveCredentials( Request $request )
    {
        ns()->option->set( 'ns_pa_app_id', $request->input( 'ns_pa_app_id' ) );
        ns()->option->set( 'ns_pa_secret_key', $request->input( 'ns_pa_secret_key' ) );

        return [
            'status'    =>  'success',
            'message'   =>  __m( 'The credentials were successfully saved.', 'NsPrintAdapter' )
        ];
    }

    public function saleReceipt( $resource )
    {
        $order  =   Order::find( $resource );
        $order->load( 'customer' );
        $order->load( 'products' );
        $order->load( 'shipping_address' );
        $order->load( 'billing_address' );
        $order->load( 'user' );

        $paymentTypes   =   PaymentType::orderBy( 'priority', 'asc' )
            ->active()
            ->get()
            ->map( function( $payment, $index ) {
                $payment->selected = $index === 0;

                return $payment;
            })->mapWithKeys( function( $payment ) {
                return [ $payment[ 'identifier' ] => $payment[ 'label' ] ];
            });

        return View::make( 'NsPrintAdapter::receipt.images.sale-receipt', compact( 'order', 'paymentTypes' ) );
    }

    public function refundReceipt( $resource )
    {
        $orderRefund    =   OrderRefund::with([ 'order', 'refunded_products.product' ])->find( $resource );
        $order          =   $orderRefund->order;

        return View::make( 'NsPrintAdapter::receipt.images.refund-receipt', compact( 'order', 'orderRefund' ) );
    }

    public function kitchenReceipt( $resource )
    {
        return View::make( 'NsPrintAdapter::receipt.images.kitchen-receipt' );
    }

    public function paymentReceipt( $resource )
    {
        $payment    =   OrderPayment::find( $resource );

        return View::make( 'NsPrintAdapter::receipt.images.payment-receipt', [
            'payment'   =>  $payment,
            'order'     =>  Order::find( $payment->order_id )
        ]);
    }

    public function testPrinter( Request $request )
    {
        $printer        =   Printer::findOrFail( $request->input( 'printer' ) );
        $testTemplate   =   View::make( 'NsPrintAdapter::test', compact( 'printer' ) )->render();
        return $this->cloudPrintService->printTemplate( $testTemplate, $printer );
    }
}
