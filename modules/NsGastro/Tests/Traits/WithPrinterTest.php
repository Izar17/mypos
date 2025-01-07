<?php
namespace Modules\NsGastro\Tests\Traits;

use App\Events\OrderAfterCreatedEvent;
use App\Events\ResponseReadyEvent;
use App\Models\Product;
use App\Services\ModulesService;
use Illuminate\Support\Facades\Event;
use Modules\NsGastro\Models\Kitchen;
use Modules\NsGastro\Models\KitchenCategory;
use Modules\NsGastro\Models\KitchenPrinter;
use Modules\NsGastro\Models\Table;
use Modules\NsPrintAdapter\Events\AfterPrintJobsCreatedEvent;
use Modules\NsPrintAdapter\Events\AfterSubmitCloudPrintJobEvent;
use Modules\NsPrintAdapter\Events\BeforeSubmitCloudPrintJobEvent;
use Tests\Traits\WithOrderTest;

trait WithPrinterTest
{
    use WithOrderTest;

    public function attemptSplitPrint()
    {
        /**
         * Note
         * ===============================================================================
         * In order for this test to work, you'll need to have kitchen set and all kitchen
         * must have assigned categories and printers. Each category assigned should have 
         * product defined and valid for sale (not grouped).
         */
        
        /**
         * Let's resolve the module service
         * @var ModulesService $moduleService
         */
        $moduleService  =   app()->make( ModulesService::class );

        $kitchens           =   Kitchen::get();

        $eventDispatched    =   [
            OrderAfterCreatedEvent::class => false,
            AfterPrintJobsCreatedEvent::class => false,
            BeforeSubmitCloudPrintJobEvent::class => false,
            AfterSubmitCloudPrintJobEvent::class => false,
        ];

        Event::listen( OrderAfterCreatedEvent::class, function() use ( &$eventDispatched ) {
            $eventDispatched[ OrderAfterCreatedEvent::class ] = true;
        });
        Event::listen( BeforeSubmitCloudPrintJobEvent::class, function() use ( &$eventDispatched ) {
            $eventDispatched[ BeforeSubmitCloudPrintJobEvent::class ] = true;
        });
        Event::listen( AfterSubmitCloudPrintJobEvent::class, function() use ( &$eventDispatched ) {
            $eventDispatched[ AfterSubmitCloudPrintJobEvent::class ] = true;
        });
        Event::listen( AfterPrintJobsCreatedEvent::class, function( $event ) use ( &$eventDispatched, $kitchens ) {
            $eventDispatched[ AfterPrintJobsCreatedEvent::class ] = true;
            $this->assertSame( count( $event->printjobs ), $kitchens->count(), 'The split print failed. There is not enough print jobs as there are kitchens.' );
        });
        
        $kitchensCategories =   $kitchens->map( function( $kitchen ) {
            $kitchenCategory     =   KitchenCategory::with([
                'category.products' => function( $query ) {
                    $query->limit(1);
                }
            ])->where( 'kitchen_id', $kitchen->id )->first();

            $kitchenData  =  $kitchen->toArray();

            $kitchenData[ 'category' ]    =   [
                ...$kitchenCategory->toArray(),
                'products'  =>  $kitchenCategory->categories->products->toArray()
            ];

            return $kitchenData;
        });

        $result     =   $kitchensCategories->toArray();

        $products   =   collect( $result )->pluck( 'category.category.products' )
            ->map( function( $products ) {
                return collect( $products )->map( fn( $product ) => $product[ 'id' ] );
            })
            ->flatten()
            ->toArray();

        /**
         * Let's retreive the order
         */
        $table = Table::busy(false)->first();

        /**
         * Let's define the order details
         */
        $orderDetails = [
            'table'                 =>  $table->toArray(),
            'gastro_order_status'   =>  'pending',
            'productsRequest'       =>  function() use ( $products ) {
                return Product::whereIn( 'id', $products )->get();
            }
        ];

        $this->allowQuickProducts   =   false;

        $this->processOrders( $orderDetails, function ($response, $data ) use ( &$eventDispatched, $moduleService ) {
            $this->assertTrue( $eventDispatched[ OrderAfterCreatedEvent::class ], 'The event OrderAfterCreatedEvent was not dispatched' );

            if ( ns()->option->get( 'ns_pa_cloud_print' ) === 'yes' && $moduleService->getIfEnabled( 'NsPrintAdapter' ) ) {
                $this->assertTrue( $eventDispatched[ AfterPrintJobsCreatedEvent::class ], 'The event AfterPrinJobsCreatedEvent wasn\'t disaptched' );
                $this->assertTrue( $eventDispatched[ BeforeSubmitCloudPrintJobEvent::class ], 'The event BeforeSubmitCloudPrintJobEvent wasn\'t disaptched' );
                $this->assertTrue( $eventDispatched[ AfterSubmitCloudPrintJobEvent::class ], 'The event AfterSubmitCloudPrintJobEvent wasn\'t disaptched' );
            }
        });
    }
}