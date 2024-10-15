@inject( 'ordersService', 'App\Services\OrdersService' )
@extends( 'layout.base' )
@section( 'layout.base.body' )
<div id="print-wrapper">
    <div class="w-full h-full">
        <div class="w-full md:w-1/2 lg:w-1/3 shadow-lg bg-white p-2 mx-auto">
            <div class="flex items-center justify-center">
                @if ( empty( ns()->option->get( 'ns_invoice_receipt_logo' ) ) )
                <h3 class="text-3xl text-black font-bold">{{ ns()->option->get( 'ns_store_name' ) }}</h3>
                @else
                <img src="{{ ns()->option->get( 'ns_invoice_receipt_logo' ) }}" alt="{{ ns()->option->get( 'ns_store_name' ) }}">
                @endif
            </div>
            <div class="p-2">
                <div class="flex flex-wrap -mx-2 text-sm py-4">
                    <div class="px-2 w-1/2 text-xl">
                        {!! nl2br( $ordersService->orderTemplateMapping( 'ns_invoice_receipt_column_a', $order ) ) !!}
                    </div>
                    <div class="px-2 w-1/2 text-xl">
                        {!! nl2br( $ordersService->orderTemplateMapping( 'ns_invoice_receipt_column_b', $order ) ) !!}
                    </div>
                </div>
            </div>
            <div class="flex justify-between py-4">
                <span>
                    {{ __m( 'Order', 'NsPrintAdapter' ) }}
                </span>
                <span>{{ $order->code }}</span>
            </div>
            <div class="border-t border-black"></div>
            <div class="flex justify-between py-4">
                <span>
                    {{ __m( 'Total', 'NsPrintAdapter' ) }}
                </span>
                <span>{{ ns()->currency->define( $order->total ) }}</span>
            </div>
            <div class="border-t border-black"></div>
            <div class="flex justify-between py-4">
                <span>
                    {{ __m( 'Paid', 'NsPrintAdapter' ) }}
                </span>
                <span>{{ ns()->currency->define( $payment->value ) }}</span>
            </div>
            <div class="border-t border-black"></div>
            <div class="flex justify-between py-4">
                <span>
                    {{ __m( 'Due', 'NsPrintAdapter' ) }}
                </span>
                <span>{{ ns()->currency->define( $order->total - ( $order->tendered ) ) }}</span>
            </div>
            <div class="border-t border-black"></div>
            <div class="flex justify-center py-4">
                {{  
                    sprintf(
                        __m( 'A payment of %s has been reiceved today from %s, for the order %s which total is %s and so far was paid %s.', 'NsPrintAdapter' ),
                        ns()->currency->define( $payment->value ),
                        $order->customer->name,
                        $order->code,
                        ns()->currency->define( $order->total ),
                        ns()->currency->define( $order->tendered )
                    ) 
                }}
            </div>
        </div>
    </div>
</div>
@endsection