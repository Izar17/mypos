<?php
use App\Models\Order;
use App\Services\OrdersService;

$line           =   '*';
$ordersServices     =   app()->make( OrdersService::class );
$types              =   $ordersServices->getTypeLabels();
?>

<{!! '?xml version="1.0" encoding="UTF-8"?' !!}>
<document>
    <align mode="center">
        <bold>
            <text-line size="3:3">
            {{
                sprintf( __m( 'Canceled Meals for %s' ), $order->code )
            }}
            </text-line>
        </bold>
    </align>
    <line-feed></line-feed>
    @if ( ! empty( ns()->option->get( 'ns_gastro_cancelation_note' ) ) )
    <align mode="center">
        <text-line>{{ ns()->option->get( 'ns_gastro_cancelation_note' ) }}</text-line>
    </align>
    <line-feed></line-feed>
    @endif
    <align mode="left">
        <text-line>
        <?php echo $printService->nexting([
            __m( 'Date', 'NsGastro' ),
            ns()->date->getFormatted( $order->created_at )
        ]);
        ?></text-line>
        <text-line>
        <?php echo $printService->nexting([
            __m( 'Table', 'NsGastro' ),
            $order->table_name
        ]);
        ?></text-line>
        <text-line>
        <?php echo $printService->nexting([
            __m( 'Code', 'NsGastro' ),
            $order->code
        ]);
        ?></text-line>
        <text-line>
        <?php echo $printService->nexting([
            __m( 'Kitchen', 'NsGastro' ),
            $kitchen->name
        ]);
        ?></text-line>
        <text-line>
        <?php echo $printService->nexting([
            __m( 'By', 'NsGastro' ),
            $order->user->username
        ]);
        ?></text-line>
        <text-line>
        <?php echo $printService->nexting([
            __m( 'Order Type', 'NsGastro' ),
            $types[ $order->type ] ?? __( 'N/A' )
        ]);
        ?></text-line>
    </align>
    <line-feed></line-feed>
    <text>
        <text-line>{{ __( 'Products' ) }}</text-line>
        @foreach( $products as $product )
            <text-line><?php echo $printService->nexting([], '-');?></text-line>
            <text-line><?php echo $printService->nexting([
                $product->name,
                ' (x' . $product->meal_cancelation_quantity . ')'
            ]);?></text-line>
            @foreach( $product->modifiers as $modifier )
            <text-line><?php echo $printService->nexting([
                '-> ' . $modifier->group->name . ' : ' . $modifier->name,
                ' (x' . $modifier->quantity . ')'
            ]);?></text-line> 
            @endforeach
            @if ( ! empty( $product->cooking_cancelation_note ) ) 
            <text-line>{{ sprintf( __m( 'Cancelation Note : %s', 'NsGastro' ), $product->cooking_cancelation_note ) }}</text-line>
            @endif
        @endforeach
    </text>
    <line-feed></line-feed>
    <text>
        <text-line><?php echo $printService->nexting([], '-');?></text-line>
    </text>
    <line-feed></line-feed>
    <align mode="center">
        <text-line>{{ $order->note }}</text-line>
    </align>
    <line-feed></line-feed>
    <align mode="center">
        <text-line>{{ ns()->option->get( 'ns_pa_receipt_footer' ) }}</text-line>
    </align>
    <line-feed></line-feed>
    <paper-cut></paper-cut>
</document>