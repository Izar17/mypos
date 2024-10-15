<?php

use App\Models\PaymentType;

$paymentMethod  =   PaymentType::where( 'identifier', $orderRefund->payment_method )->first();
?>
@inject( 'ordersService', 'App\Services\OrdersService' )
@extends( 'layout.base' )
@section( 'layout.base.body' )
<div id="print-wrapper">
    <div class="w-full md:w-1/2 lg:w-1/3 shadow-lg bg-white p-2 mx-auto">
        <div class="flex items-center justify-center">
            @if ( ns()->option->get( 'ns_pa_logotype' ) === 'url' )
            <img src="{{ ns()->option->get( 'ns_pa_logourl' ) }}" alt="{{ __m( 'Logo', 'NsPrintAdapter' ) }}">
            @else
            <h3 class="text-3xl font-bold">{{ ns()->option->get( 'ns_store_name', ns()->option->get( 'ns_store_name' ) ) }}</h3>
            @endif
        </div>
        <div class="p-2 border-b border-gray-700">
            <div class="flex flex-wrap -mx-2 text-sm">
                <div class="px-2 w-1/2">
                    {!! nl2br( $ordersService->orderTemplateMapping( 'ns_invoice_receipt_column_a', $order ) ) !!}
                </div>
                <div class="px-2 w-1/2">
                    {!! nl2br( $ordersService->orderTemplateMapping( 'ns_invoice_receipt_column_b', $order ) ) !!}
                </div>
            </div>
        </div>
        <div>
            <table class="table w-full">
                <thead>
                    <tr class="font-bold">
                        <td>{{ __( 'Product' ) }}</td>
                        <td>{{ __( 'Price' ) }}</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $orderRefund->refunded_products as $product ) 
                    <tr class="border-b border-gray-700">
                        <td>
                            <span>{{ $product->product->name }} (x{{ $product->quantity }})</span>
                            <br>
                            <span>{{ sprintf( __( 'Status: %s' ), $ordersService->getRefundedOrderProductLabel( $product->condition ) ) }}</span>
                        </td>
                        <td>{{ ns()->currency->define( $product->unit_price ) }}</td>
                    </tr>
                    @endforeach
                    <tr class="border-b border-black">
                        <td>{{ __m( 'Shipping', 'NsPrintAdapter' ) }}</td>
                        <td>{{ ns()->currency->define( $orderRefund->shipping ) }}</td>
                    </tr>
                    <tr class="border-b border-black">
                        <td>{{ __m( 'Total', 'NsPrintAdapter' ) }}</td>
                        <td>{{ ns()->currency->define( $orderRefund->total ) }}</td>
                    </tr>
                    <tr class="border-b border-black">
                        <td>{{ __m( 'Payment Method', 'NsPrintAdapter' ) }}</td>
                        <td>{{ $paymentMethod instanceof PaymentType ? $paymentMethod->label : __m( 'Unknown Payment' ) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br><br>
</div>
<style>
    #print-wrapper td > span:first-child, #print-wrapper td {
        font-size: 1.2em !important;
    }
    #print-wrapper td > span:last-child {
        font-size: 1.1em !important;
    }
    #print-wrapper td:last-child {
        width: 120px;
    }
</style>
@endsection