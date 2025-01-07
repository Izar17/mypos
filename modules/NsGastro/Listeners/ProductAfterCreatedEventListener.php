<?php

namespace Modules\NsGastro\Listeners;

use App\Events\ProductAfterCreatedEvent;
use Modules\NsGastro\Services\ProductModifiersService;

class ProductAfterCreatedEventListener
{
    public function __construct( public ProductModifiersService $productModifierService )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle( ProductAfterCreatedEvent $event )
    {
        $this->productModifierService->setProductModifierGroup( $event->product );
    }
}
