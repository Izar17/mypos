<?php
use App\Models\Order;
use App\Services\OrdersService;

$line           =   '*';
$ordersServices     =   app()->make( OrdersService::class );
$types              =   $ordersServices->getTypeLabels();
?>
<div id="print-wrapper" class="p-2" style="width: 580px">
    <div class="text-center">
        <span>
            {{ ns()->option->get( 'ns_store_name', ns()->option->get( 'ns_store_name' ) ) }}
        </span>
    </div>
    <div class="flex justify-between">
        <span>{{ __m( 'Date', 'NsPrintAdapter' ) }}</span>
        <span>{{ ns()->date->getFormatted( $order->created_at ) }}</span>
    </div>
    <div class="flex justify-between">
        <span>{{ __m( 'Table', 'NsPrintAdapter' ) }}</span>
        <span>{{ $order->table_name }}</span>
    </div>
    <div class="flex justify-between">
        <span>{{ __m( 'Code', 'NsPrintAdapter' ) }}</span>
        <span>{{ $order->code }}</span>
    </div>
    <div class="flex justify-between">
        <span>{{ __m( 'Kitchen', 'NsPrintAdapter' ) }}</span>
        <span>{{ $kitchen->name }}</span>
    </div>
    <div class="flex justify-between">
        <span>{{ __m( 'By', 'NsPrintAdapter' ) }}</span>
        <span>{{ $order->user->username }}</span>
    </div>
    <div class="flex justify-between">
        <span>{{ __m( 'Order Type', 'NsPrintAdapter' ) }}</span>
        <span>{{ $types[ $order->type ] ?? __( 'N/A' ) }}</span>
    </div>
    @foreach( $products as $product )
        <div class="flex justify-between">
            <span>{{ $product->name }}</span>
            <span>{{ ' (x' . $product->meal_cancelation_quantity . ')' }}</span>
        </div>
        @foreach( $product->modifiers as $modifier )
        <div class="flex justify-between">
            <span>{{ '-> ' . $modifier->group->name . ' : ' . $modifier->name }}</span>
            <span>{{ ' (x' . $modifier->quantity . ')' }}</span>    
        </div> 
        @endforeach
        @if ( ! empty( $product->cooking_cancelation_note ) ) 
        <span class="note">{{ sprintf( __m( 'Reason : %s', 'NsGastro' ), $product->cooking_cancelation_note ) }}</span>
        @endif
    @endforeach
</div>
<style>
    #print-wrapper span {
        font-size: 2em !important;
    }
    #print-wrapper span.note {
        font-size: 1.5em !important;
    }
</style>