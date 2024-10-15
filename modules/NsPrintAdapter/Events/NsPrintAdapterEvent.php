<?php

namespace Modules\NsPrintAdapter\Events;

use App\Events\OrderAfterCreatedEvent;
use App\Exceptions\NotFoundException;
use App\Models\Order;
use App\Models\Register;
use App\Models\Role;
use App\Services\ModulesService;
use App\Services\NotificationService;
use Exception;
use Illuminate\Support\Facades\Http;
use Modules\NsGastro\Events\GastroAfterCanceledOrderProductEvent;
use Modules\NsGastro\Events\GastroNewProductAddedToOrderEvent;
use Modules\NsPrintAdapter\Jobs\KitchenCloudPrintJob;
use Modules\NsPrintAdapter\Jobs\PrintOrderReceiptJob;

/**
 * Register Events
 **/
class NsPrintAdapterEvent
{
    public static function getFooter($output)
    {
        $output->addView('NsPrintAdapter::pos.footer');
    }

    public static function prepareOrderForKitchenPrint(OrderAfterCreatedEvent $event)
    {
        /**
         * @var ModulesService $modules
         */
        $modules = app()->make(ModulesService::class);

        /**
         * Dispatch the following job
         * only if Gastro is enabled.
         */
        if ($modules->getIfEnabled('NsGastro') && ns()->option->get('ns_pa_cloud_print', 'no') === 'yes') {
            KitchenCloudPrintJob::dispatch($event->order, 'kitchen' );
        }
        
    }

    public static function prepareOrderForAdditionalPrint( GastroNewProductAddedToOrderEvent $event)
    {
        /**
         * @var ModulesService $modules
         */
        $modules = app()->make(ModulesService::class);

        /**
         * Dispatch the following job
         * only if Gastro is enabled.
         */
        if ($modules->getIfEnabled('NsGastro') && ns()->option->get('ns_pa_cloud_print', 'no') === 'yes') {
            KitchenCloudPrintJob::dispatch($event->order, 'kitchen' );
        }
    }

    public static function prepareOrderForCanceledPrint( GastroAfterCanceledOrderProductEvent $event )
    {
        /**
         * @var ModulesService $modules
         */
        $modules = app()->make(ModulesService::class);

        if ( $modules->getIfEnabled('NsGastro') && ns()->option->get('ns_pa_cloud_print', 'no') === 'yes' && ( bool ) ns()->option->get( 'ns_gastro_allow_cancelation_print' ) ) {
            KitchenCloudPrintJob::dispatch( $event->product->order, 'kitchen-canceled' );
        }
    }
}
