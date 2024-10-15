<?php

use App\Classes\Currency;
use App\Models\PaymentType;
use App\Services\OrdersService;
use Modules\NsPrintAdapter\Services\PrintService;
use Modules\NsPrintAdapter\Services\ReceiptImage;

/**
 * @var OrdersService
 */
$orderService   =   app()->make( OrdersService::class );

/**
 * @var PrintService
 */
$printService   =   app()->make( PrintService::class );

$orderRefund    =   $resource;
$orderRefund->load([ 'order', 'refunded_products.product' ]);
$paymentMethod  =   PaymentType::where( 'identifier', $orderRefund->payment_method )->first();

$receipt  =   new ReceiptImage();
$receipt->alignCenter();
$receipt->setFontSize(20);
$receipt->setLineHeight(20);

if ( empty( ns()->option->get( 'ns_pa_logourl' ) ) ) {
    $receipt->write( ns()->option->get( 'ns_store_name', __m( 'Unammed Store', 'NsPrintAdapter' ) ) );
} else {
    $receipt->addImage( ns()->option->get( 'ns_pa_logourl' ) );
}

$receipt->newLine(4);

foreach( $printService->buildingLines( 
    $orderService->orderTemplateMapping( 'ns_pa_left_column', $resource->order ),
    $orderService->orderTemplateMapping( 'ns_pa_right_column', $resource->order ),
) as $line ):
    $receipt->writeLine(
        left: $line[0],
        right: $line[1]
    );
    $receipt->newLine();
endforeach;

$receipt->newLine();
$receipt->drawSeparator( 'dashed' );
$receipt->newLine();

$orderRefund->refunded_products->each( function( $refundedProduct ) use ( $receipt ) {
    $receipt->writeLine(
        left: $refundedProduct->product->name . ' (x' . $refundedProduct->quantity . ')',
        right: Currency::define( $refundedProduct->total_price )
    );
    $receipt->newLine();
} );

$receipt->drawSeparator( 'dashed' );
$receipt->newLine();

$receipt->writeLine(
    left: __m( 'Shipping', 'NsPrintAdapter' ),
    right: Currency::define( $orderRefund->shipping )
);

$receipt->newLine();

$receipt->writeLine(
    left: __m( 'Total', 'NsPrintAdapter' ),
    right: Currency::define( $orderRefund->total )
);

$receipt->newLine();

$receipt->writeLine(
    left: __m( 'Payment Method', 'NsPrintAdapter' ),
    right: $paymentMethod instanceof PaymentType ? $paymentMethod->label : __m( 'Unknown Payment' )
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
    <interface>{{ $printService->getPrinterInterface( $printer ) }}</interface>
    <type>{{ $printer->type ?? 'epson' }}</type>
    <line-character>{{ $printer->line_character ?? '*' }}</line-character>
</configuration>
<document>
    <base64>{{ $base64 }}</base64>
    <paper-cut></paper-cut>
</document>