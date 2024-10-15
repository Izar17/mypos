@inject( 'printerService', 'Modules\NsPrintAdapter\Services\PrintService' )
<?php

use App\Classes\Currency;
use App\Services\OrdersService;
use Modules\NsGastro\Models\Order;
use Modules\NsPrintAdapter\Services\PrintService;
use Modules\NsPrintAdapter\Services\ReceiptImage;

$order  =   Order::with( 'table', 'user' )->findOrFail( $resource->id );

/**
 * @var OrdersService
 */
$orderService   =   app()->make( OrdersService::class );

/**
 * @var PrintService
 */
$printService   =   app()->make( PrintService::class );

$receipt  =   new ReceiptImage();
$receipt->setFontSize(30);
$receipt->setLineHeight(30);
$receipt->alignCenter();

if ( empty( ns()->option->get( 'ns_pa_logourl' ) ) ) {
    $receipt->write( ns()->option->get( 'ns_store_name', __m( 'Unammed Store', 'NsPrintAdapter' ) ) );
} else {
    $receipt->addImage( ns()->option->get( 'ns_pa_logourl' ) );
}

$receipt->setFontSize(20);
$receipt->setLineHeight(20);
$receipt->newLine(4);

foreach( $printService->buildingLines( 
    $orderService->orderTemplateMapping( 'ns_pa_left_column', $order ),
    $orderService->orderTemplateMapping( 'ns_pa_right_column', $order ),
) as $line ):
    $receipt->writeLine(
        left: $line[0],
        right: $line[1]
    );
    $receipt->newLine();
endforeach;

$receipt->drawSeparator( 'dashed' );
$receipt->newLine();

$receipt->setFontFamily( ns()->option->get( 'ns_pa_font_familly', 'roboto' ), 'bold' );
$receipt->write( __m( 'Products', 'NsPrintAdapter' ) );
$receipt->setFontFamily( ns()->option->get( 'ns_pa_font_familly', 'roboto' ) );
$receipt->drawSeparator( 'dashed' );
$receipt->newLine();

$order->products->each( function( $product ) use ( $receipt ) {
    $receipt->writeLine(
        left: $product->name . ' (x' . $product->quantity . ')',
        right: Currency::define( $product->unit_price )
    );
    $receipt->newLine();
} );

$receipt->drawSeparator( 'dashed' );
$receipt->newLine();

// receipt footer
$receipt->writeLine(
    left: __m( 'SubTotal', 'NsPrintAdapter' ),
    right: Currency::define( $order->subtotal )
);
$receipt->newLine();
$receipt->writeLine(
    left: __m( 'Discount', 'NsPrintAdapter' ),
    right: Currency::define( $order->discount )
);
$receipt->newLine();
$receipt->writeLine(
    left: __m( 'Shipping', 'NsPrintAdapter' ),
    right: Currency::define( $order->shipping )
);
$receipt->newLine();
$receipt->writeLine(
    left: __m( 'Total', 'NsPrintAdapter' ),
    right: Currency::define( $order->total )
);


$receipt->drawSeparator( 'dashed' );
$receipt->newLine(3);
$receipt->alignCenter();
$receipt->write( ns()->option->get( 'ns_pa_receipt_footer' ) );

$base64 =   $receipt->getBase64();
?>
<{!! '?xml version="1.0" encoding="UTF-8"?' !!}>
<configuration>
    <characterset>{{ $printer->characterset }}</characterset>
    <interface>{{ $printerService->getPrinterInterface( $printer ) }}</interface>
    <type>{{ $printer->type ?? 'epson' }}</type>
    <line-character>{{ $printer->line_character ?? '*' }}</line-character>
</configuration>
<document>
    <base64>{{ $base64 }}</base64>
    <paper-cut></paper-cut>
</document>