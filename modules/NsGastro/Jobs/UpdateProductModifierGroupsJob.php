<?php
namespace Modules\NsGastro\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\NsGastro\Services\ProductModifiersService;

/**
 * Register Job
**/
class UpdateProductModifierGroupsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Here you'll resolve your services.
     */
    public function __construct( public Product $product )
    {
        
    }

    /**
     * Here your jobs is being executed
     */
    public function handle( ProductModifiersService $productModifiersService )
    {
        $productModifiersService->setProductModifierGroup( $this->product );
    }
}