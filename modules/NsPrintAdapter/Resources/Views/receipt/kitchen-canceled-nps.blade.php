<?php
use App\Classes\Hook;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderRefund;
use App\Models\PaymentType;
use App\Services\OrdersService;
use Modules\NsGastro\Models\Kitchen;
use Modules\NsGastro\Models\KitchenPrinter;
use Modules\NsGastro\Models\Order as GastroreOrder;
use Modules\NsPrintAdapter\Services\PrintService;

/**
 * @var OrderService $orderService
 */
$orderService = app()->make( OrdersService::class );

/**
 * @var PrintService $printerService
 */
$printerService = app()->make( PrintService::class );

$products   =   $printerService->getPrintableProducts( $resource, $data[ 'categories' ], [
    'meal_cancelation_printed'  =>  false,
    'cooking_status'    =>  'canceled'
]);

$kitchen    =   KitchenPrinter::where( 'printer_id', $printer->id )->first()->kitchen;

/**
 * @var Order $resource
 * @var Printer $printer
 * @var string $document
 * @var array $data
 */
?>
<{!! '?xml version="1.0" encoding="UTF-8"?' !!}>
<configuration>
    <characterset>{{ $printer->characterset }}</characterset>
    <interface>{{ $printerService->getPrinterInterface( $printer ) }}</interface>
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
        <text-line>
        <?php echo $printerService->nexting([
            __m( 'Date', 'NsGastro' ),
            ns()->date->getFormatted( $resource->created_at )
        ]);
        ?></text-line>
        <text-line>
        <?php echo $printerService->nexting([
            __m( 'Table', 'NsGastro' ),
            $resource->table_name
        ]);
        ?></text-line>
        <text-line>
        <?php echo $printerService->nexting([
            __m( 'Code', 'NsGastro' ),
            $resource->code
        ]);
        ?></text-line>
        <text-line>
        <?php echo $printerService->nexting([
            __m( 'Kitchen', 'NsGastro' ),
            $data[ 'kitchen' ]->name
        ]);
        ?></text-line>
        <text-line>
        <?php echo $printerService->nexting([
            __m( 'By', 'NsGastro' ),
            $resource->user->username
        ]);
        ?></text-line>
        <text-line>
        <?php echo $printerService->nexting([
            __m( 'Order Type', 'NsGastro' ),
            $types[ $resource->type ] ?? __( 'N/A' )
        ]);
        ?></text-line>
    </align>
    <line-feed></line-feed>
    <align mode="center">
        <bold>
            <text-line size="3:3">
            {{
                sprintf( __m( 'Canceled Meals for %s' ), $resource->code )
            }}
            </text-line>
        </bold>
    </align>
    <text>
        <text-line>{{ __( 'Products' ) }}</text-line>
        <?php foreach( $products as $product ):?>
            <text-line><?php echo $printerService->nexting([], '-');?></text-line>
            <text-line><?php echo $printerService->nexting([
                $product->name,
                ' (x' . $product->meal_cancelation_quantity . ')'
            ]);?></text-line>
            @foreach( $product->modifiers as $modifier )
            <text-line><?php echo $printerService->nexting([
                '- ' . $modifier->group->name . ' : ' . $modifier->name,
                ' (x' . $modifier->quantity . ')'
            ]);?></text-line> 
            @endforeach
            @if ( ! empty( $product->cooking_cancelation_note ) ) 
            <text-line>{{ sprintf( __m( 'Note : %s', 'NsGastro' ), $product->cooking_cancelation_note ) }}</text-line>
            @endif

            <?php
            $product->meal_cancelation_printed = true;
            $product->save();
            ?>
        <?php endforeach;?>
    </text>
    <line-feed></line-feed>
    <text>
        <text-line><?php echo $printerService->nexting([], '-');?></text-line>
    </text>
    <line-feed></line-feed>
    <align mode="center">
        <text-line>{{ $resource->note }}</text-line>
    </align>
    <line-feed></line-feed>
    <paper-cut></paper-cut>
</document>