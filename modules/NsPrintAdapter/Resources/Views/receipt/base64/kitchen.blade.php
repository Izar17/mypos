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

$products   =   $printService->getPrintableProducts( $resource, $data[ 'categories' ], [
    'meal_printed'  =>  false,
    'cooking_status'    =>  'pending'
]);

$receipt  =   new ReceiptImage();

$receipt->setFontSize(20);

$receipt->alignCenter();
$receipt->write( __m( 'Kitchen Receipt', 'NsPrintAdapter' ) );
$receipt->newLine(3);
$receipt->drawSeparator( 'dashed' );
$receipt->newLine();

$receipt->writeLine(
    left: __m( 'Table', 'NsPrintAdapter' ),
    right: $order->table->name ?? __m( 'N/A', 'NsPrintAdapter' )
);
$receipt->newLine();
$receipt->writeLine(
    left: __m( 'Kitchen', 'NsPrintAdapter' ),
    right: $data[ 'kitchen' ]->name ?? __m( 'N/A', 'NsPrintAdapter' )
);
$receipt->newLine();
$receipt->writeLine(
    left: __m( 'Order Type', 'NsPrintAdapter' ),
    right: $orderService->getTypeLabel( $order->type ) ?? '-'
);
$receipt->newLine();
$receipt->writeLine(
    left: __m( 'By', 'NsPrintAdapter' ),
    right: $order->user->username ?? '-'
);

$receipt->newLine();
$receipt->drawSeparator( 'dashed' );
$receipt->newLine();

$products->each( function( $product ) use ( $receipt ) {
    $receipt->setFontSize(20);
    $receipt->writeLine(
        left: $product->name,
        right: Currency::define( $product->unit_price )
    );
    $receipt->newLine();

    if ( $product->modifiers()->count() > 0 ) {
        $product->modifiers()->get()->each( function( $modifier ) use ( $receipt ) {
            $receipt->setFontSize(15);
            $receipt->writeLine(
                left: '-> ' . $modifier->name,
                right: Currency::define( $modifier->unit_price )
            );       
            $receipt->newLine(); 
        });
    }
});

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