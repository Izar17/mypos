<?php

namespace Modules\NsGastro\Listeners;

use App\Events\ProductAfterUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\NsGastro\Services\ProductModifiersService;

class ProductAfterUpdatedEventListener
{
    public function __construct( public ProductModifiersService $productModifiersService )
    {
        
    }
    /**
     * Handle the event.
     */
    public function handle( ProductAfterUpdatedEvent $event )
    {
        $this->productModifiersService->setProductModifierGroup( $event->product );
    }
}
