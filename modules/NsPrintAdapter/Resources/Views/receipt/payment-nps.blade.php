<{!! '?xml version="1.0" encoding="UTF-8"?' !!}>
<?php

use App\Services\OrdersService;
use Modules\NsPrintAdapter\Services\PrintService;

/**
 * @var OrdersService $orderService;
 */
$orderService  =   app()->make( OrdersService::class );

/**
 * @var PrintService $printService;
 */
$printService  =   app()->make( PrintService::class );

$orderPayment   =   $resource;
$orderPayment->load( 'order' );

$order          =   $orderPayment->order;
?>
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
            $orderService->orderTemplateMapping( 'ns_pa_left_column', $order ),
            $orderService->orderTemplateMapping( 'ns_pa_right_column', $order ),
        ) as $line ):?>
        <text-line><?php echo $printService->nexting( $line );?></text-line>
        <?php endforeach;?>
    </align>
    <line-feed></line-feed>
    <text>
        <text-line><?php echo $printService->nexting([], '=');?></text-line> 
        <align mode="center">
            <text-line>{{ __m( 'Payment Receipt', 'NsPrintAdapter' ) }}</text-line>
        </align>
        <text-line><?php echo $printService->nexting([], '=');?></text-line>      
        <text-line><?php echo $printService->nexting([
            __m( 'Total', 'NsPrintAdapter' ),
            ns()->currency->define( $order->total )
        ]);?></text-line>
        <text-line><?php echo $printService->nexting([], '-');?></text-line>      
        <text-line><?php echo $printService->nexting([
            __m( 'Paid', 'NsPrintAdapter' ),
            ns()->currency->define( $orderPayment->value )
        ]);?></text-line>
        <text-line><?php echo $printService->nexting([], '-');?></text-line>      
        <text-line><?php echo $printService->nexting([
            __m( 'Due', 'NsPrintAdapter' ),
            ns()->currency->define( $order->total - ( $order->tendered - $orderPayment->value ) )
        ]);?></text-line>
        <text-line><?php echo $printService->nexting([], '-');?></text-line>      
        <text-line><?php echo $printService->nexting([
            __m( 'Order', 'NsPrintAdapter' ),
            $order->code
        ]);?></text-line>
        <text-line><?php echo $printService->nexting([], '-');?></text-line>      
        <line-feed></line-feed>
        <align mode="center">
            <text-line>
            {{  
                sprintf(
                    __m( 'A payment of %s has been reiceved today from %s, for the order %s which total is %s and so far was paid %s.', 'NsPrintAdapter' ),
                    ns()->currency->define( $orderPayment->value ),
                    $order->customer->name,
                    $order->code,
                    ns()->currency->define( $order->total ),
                    ns()->currency->define( $order->tendered )
                ) 
            }}
            </text-line>
        </align>
        <paper-cut></paper-cut>
    </text>
</document>