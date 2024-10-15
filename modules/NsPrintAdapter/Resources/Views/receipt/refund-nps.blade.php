<?php

use App\Classes\Hook;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderRefund;
use App\Models\PaymentType;
use App\Services\OrdersService;
use Modules\NsPrintAdapter\Services\PrintService;

/**
 * @var OrderRefund
 */
$orderRefund    =   $resource;
$orderRefund->load([ 'order', 'refunded_products.product' ]);
$paymentMethod  =   PaymentType::where( 'identifier', $orderRefund->payment_method )->first();

/**
 * @var OrdersService $orderService
 */
$orderService   =   app()->make( OrdersService::class );

/**
 * @var PrintService $orderService
 */
$printService   =   app()->make( PrintService::class );
?>
<{!! '?xml version="1.0" encoding="UTF-8"?' !!}>
<configuration>
    <characterset>{{ $printer->characterset }}</characterset>
    <interface>{{ $printService->getPrinterInterface( $printer ) }}</interface>
    <type>{{ $printer->type ?? 'epson' }}</type>
    <line-character>{{ $printer->line_character ?? '*' }}</line-character>
</configuration>
<document>
    <align mode="center">
        <bold>
            @if( ns()->option->get( 'ns_pa_logotype' ) === 'image' )
            <text-line size="3:3">
                {{ ns()->option->get( 'ns_pa_logoshortcode' ) }}
            </text-line>
            @elseif ( ns()->option->get( 'ns_pa_logotype' ) === 'url' )
            <image>{{ ns()->option->get( 'ns_pa_logourl' ) }}</image>
            @else
            <text-line size="3:3">
                {{ ns()->option->get( 'ns_store_name', ns()->option->get( 'ns_store_name' ) ) }}
            </text-line>
            @endif
        </bold>
    </align>
    <line-feed></line-feed>
    <align mode="left">
        <?php foreach( $printService->buildingLines( 
            $orderService->orderTemplateMapping( 'ns_pa_left_column', $orderRefund->order ),
            $orderService->orderTemplateMapping( 'ns_pa_right_column', $orderRefund->order ),
        ) as $line ):?>
        <text-line><?php echo $printService->nexting( $line );?></text-line>
        <?php endforeach;?>
    </align>
    <line-feed></line-feed>
    <text>
        <text-line>{{ __m( 'Products', 'NsPrintAdapter' ) }}</text-line>
        @foreach( $orderRefund->refunded_products as $refundedProduct )
        <text-line><?php echo $printService->nexting([], '-');?></text-line>
        <text-line>
        <?php echo $printService->nexting([
            $refundedProduct->product->name . ' - ' . $refundedProduct->unit->name . ' (x' . $refundedProduct->quantity . ')',
            ns()->currency->define( $refundedProduct->total_price )
        ]);
        ?></text-line>
        <text-line>->{{ __m( 'Status', 'NsPrintAdapter' ) }}: {{ $orderService->getRefundedOrderProductLabel( $refundedProduct->condition ) }}</text-line>
        <?php echo Hook::filter( 'ns-pa-refund-receipt-after-product', '', $refundedProduct );?>
        @endforeach
    </text>
    <line-feed></line-feed>
    <text>
        <text-line><?php echo $printService->nexting([], '*');?></text-line>   
    </text>
    <bold>
        <text-line>
        <?php echo $printService->nexting([
            __m( 'Shipping', 'NsPrintAdapter' ),
            ns()->currency->define( $orderRefund->shipping )
        ]);?></text-line>
        <text-line><?php echo $printService->nexting([], '-');?></text-line>        
        <text-line>
        <?php echo $printService->nexting([
            __m( 'Total', 'NsPrintAdapter' ),
            ns()->currency->define( $orderRefund->total )
        ]);?></text-line>
        <text-line><?php echo $printService->nexting([], '-');?></text-line>        
        <text-line>
        <?php echo $printService->nexting([
            __m( 'Payment Method', 'NsPrintAdapter' ),
            $paymentMethod instanceof PaymentType ? $paymentMethod->label : __m( 'Unknown Payment' )
        ]);?></text-line>
        <text-line><?php echo $printService->nexting([], '-');?></text-line>        

    </bold>
    <paper-cut></paper-cut>
</document>