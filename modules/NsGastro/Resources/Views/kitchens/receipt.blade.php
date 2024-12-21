<?php
use App\Models\Order;
use App\Services\OrdersService;

$line           =   '*';
$ordersServices     =   app()->make( OrdersService::class );
$types              =   $ordersServices->getTypeLabels();
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
            <text-line size="3:3">
            {{ ns()->option->get( 'ns_store_name', ns()->option->get( 'ns_store_name' ) ) }}
            </text-line>
        </bold>
    </align>
    <line-feed></line-feed>
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
                ' (x' . $product->quantity . ')'
            ]);?></text-line>
            @foreach( $product->modifiers as $modifier )
            <text-line><?php echo $printService->nexting([
                '- ' . $modifier->group->name . ' : ' . $modifier->name,
                ' (x' . $modifier->quantity . ')'
            ]);?></text-line> 
            @endforeach
            @if ( ! empty( $product->cooking_note ) ) 
            <text-line>{{ sprintf( __m( 'Note : %s', 'NsGastro' ), $product->cooking_note ) }}</text-line>
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
    <paper-cut></paper-cut>
</document>