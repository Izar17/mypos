<?php
use App\Models\Order;
use App\Classes\Hook;
use Illuminate\Support\Facades\View;

?>
<div class="w-full h-full ">
    <div class="w-full md:w-1/2 lg:w-1/3 shadow-lg bg-white p-1 mx-auto">
        <div class="flex items-center justify-center">
            @if ( empty( ns()->option->get( 'ns_invoice_receipt_logo' ) ) )
            <h3 class="text-sm font-bold">{{ ns()->option->get( 'ns_store_name' ) }}</h3>
            @else
            <img src="{{ ns()->option->get( 'ns_invoice_receipt_logo' ) }}" alt="{{ ns()->option->get( 'ns_store_name' ) }}">
            @endif
        </div>
        <div class="p-1 border-b border-gray-700">
            <div class="flex flex-wrap -mx-2 text-xs">
                <div class="px-2 w-1/2">
                    {!! nl2br( $ordersService->orderTemplateMapping( 'ns_invoice_receipt_column_a', $order ) ) !!}
                </div>
                <div class="px-2 w-1/2">
                    {!! nl2br( $ordersService->orderTemplateMapping( 'ns_invoice_receipt_column_b', $order ) ) !!}
                </div>
            </div>
        </div>
        <div class="table w-full">
            <table class="w-full">
                <thead>
                    <tr class="font-semibold">
                        <td colspan="2" class="p-1 border-b border-gray-800 text-xs">{{ __( 'Product' ) }}</td>
                        <td class="p-1 border-b border-gray-800 text-right text-xs">{{ __( 'Total' ) }}</td>
                    </tr>
                </thead>
                <tbody class="text-xs">
                @php $totalQuantity = 0; @endphp
                    @foreach( Hook::filter( 'ns-receipt-products', $order->combinedProducts ) as $product )
                    @php $totalQuantity += $product->quantity; @endphp
                    <tr>
                        <td colspan="2" class="p-0 ">
                            <?php $productName  =   View::make( 'pages.dashboard.orders.templates._product-name', compact( 'product' ) );?>
                            <?php echo Hook::filter( 'ns-receipt-product-name', $productName->render(), $product );?>
                        </td>
                        <td class="p-0 text-right">
                            {{ ns()->currency->define( $product->total_price) }}</td>
                    </tr>
                    @endforeach
                    <tr><td colspan="3" style="text-align:left; font-size:16;"><hr>Qty:{{ $totalQuantity }}</td></tr>
                </tbody>
                <tbody>
                    <tr>
                        <td colspan="2" class="p-0  text-xs font-semibold border-t border-gray-800">{{ __( 'Gross Sale' ) }}</td>
                        <td class="p-0  text-xs text-right border-t border-gray-800 font-semibold">{{ ns()->currency->define( $order->subtotal) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="p-0 text-xs  border-t border-gray-800">
                            <span>Less {{ __( 'Vat Exempt' ) /* __( 'Taxes' ) */ }}</span>
                        </td>
                        <td class="p-0 text-xs text-right border-t border-gray-800">{{ ns()->currency->define( $order->vat_exempt_sales * 0.12 ) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="p-0  text-xs">{{ __( 'Sub Total' ) }} (Net of Vat)</td>
                        <td class="p-0  text-xs text-right">{{ ns()->currency->define( $order->subtotal -  ($order->vat_exempt_sales * 0.12)) }}</td>
                    </tr>
                    {{-- @if ( $order->discount > 0 ) --}}
                    <tr>
                        <td colspan="2" class="p-0  text-xs">
                            <span>{{ __( 'Less Discount' ) }}&nbsp; </span>
                            @if ( $order->discount_code != '' )
                            <span>{{ $order->discount_code }}-</span>
                            @endif
                            @if ( $order->discount_type === 'percentage' )
                            <span>({{ $order->discount_percentage }}%)</span>
                            @endif
                            
                        </td>
                        <td class="p-0  text-xs text-right">{{ ns()->currency->define( $order->discount ) }}</td>
                    </tr>
                    {{-- @endif --}}
                    <tr>
                        <td colspan="2" class="p-0  text-xs">{{ __( 'Sub Total' ) }} (Net of Disc)</td>
                        <td class="p-0  text-xs text-right">{{ ns()->currency->define($order->subtotal - $order->discount - $order->vat_exempt  ) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="p-0  text-xs">{{ __( 'Service Charge 10%' ) }}</td>
                        <td class="p-0  text-xs text-right">{{ ns()->currency->define( $order->service_charge ) }}</td>
                    </tr>
                    
                    @if ( $order->total_coupons > 0 )
                    <tr>
                        <td colspan="2" class="p-0  text-xs">
                            <span>{{ __( 'Coupons' ) }}</span>
                        </td>
                        <td class="p-0  text-xs text-right">{{ ns()->currency->define( $order->total_coupons ) }}</td>
                    </tr>
                    @endif
                    
                    @if ( $order->shipping > 0 )
                    <tr>
                        <td colspan="2" class="p-0  text-xs">{{ __( 'Shipping' ) }}</td>
                        <td class="p-0  text-xs text-right">{{ ns()->currency->define( $order->shipping ) }}</td>
                    </tr>
                    @endif
                    
                    @if ($order->number_pax >=1)
                    <tr>
                        <td colspan="2" class="p-0 border-b border-gray-800 text-xs">No. of PAX/Discounted</td>
                        <td class="p-0 border-b border-gray-800 text-xs text-right">{{ $order->number_pax }}/{{ $order->number_pax_discount }}</td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="2" class="p-0 border-b border-gray-800 text-xs"></td>
                        <td class="p-0 border-b border-gray-800 text-xs"></td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="2" class="p-0 border-b border-gray-800 text-xs font-semibold">{{ __( 'Amount Due' ) }}</td>
                        <td class="p-0 border-b border-gray-800 text-xs text-right font-semibold">{{ ns()->currency->define( $order->total ) }}</td>
                    </tr>
                    @if ( ns()->option->get( 'ns_invoice_display_tax_breakdown' ) === 'yes' ) 
                        {{-- @foreach( $order->taxes as $tax )
                        <tr>
                            <td colspan="2" class="p-0 border-b border-gray-800 text-xs font-semibold">
                                <span>{{ $tax->tax_name }} &mdash; {{ $order->tax_type === 'inclusive' ? __( 'Inclusive' ) : __( 'Exclusive' ) }}</span>
                            </td>
                            <td class="p-0">{{ ns()->currency->define( $tax->tax_value ) }}</td>
                        </tr>
                        @endforeach
                        @if ( $order->products_tax_value > 0 )
                        <tr>
                            <td colspan="2" class="p-0 border-b border-gray-800 text-xs font-semibold">
                                <span>{{ $order->tax_type === 'inclusive' ?    __( 'Inclusive Product Taxes' )  : __( 'Exclusive Product Taxes' ) }}</span>
                            </td>
                            <td class="p-0">{{ ns()->currency->define( $order->products_tax_value ) }}</td>
                        </tr>
                        @endif --}}
                    @else                     
                        @if ( $order->total_tax_value > 0 )
                        {{-- LCABORNAY --}}
                        <tr>
                            <td colspan="2" class="p-0  text-xs ">
                                <span>{{ __( 'Taxable Sale' )  }}</span>
                            </td>
                            @if($order->discount_code == 'FREE')
                            <td class="p-0 text-xs text-right">0.00</td>
                            @elseif( $order->discount_code == 'SC' || $order->discount_code == 'PWD' )  		
                                @if($order->number_pax > 1)			
                                <td class="p-0 text-xs text-right">{{ ns()->currency->define((($order->number_pax - $order->number_pax_discount)*((($order->subtotal - $order->sc_vatable) / $order->number_pax)+$order->sc_vatable))/1.12 )}}</td>
                                <!-- <td class="p-0 text-xs text-right">{{ ns()->currency->define( ($order->number_pax - $order->number_pax_discount)*($order->subtotal / $order->number_pax)  - ((($order->number_pax - $order->number_pax_discount)*($order->subtotal / $order->number_pax)/1.12)*0.12))}}</td> -->
                                @else
                                    <td class="p-0 text-xs text-right">{{ ns()->currency->define( $order->sc_vatable ) }}</td>	
                                @endif								  
                            @else
                                <td class="p-0 text-xs text-right">{{ ns()->currency->define($order->subtotal/1.12) }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td colspan="2" class="p-0  text-xs ">
                                <span>{{ __( 'Non-Taxable Sale' )  }}</span>
                            </td>
                            
                            @if($order->discount_code == 'FREE')
                            <td class="p-0 text-xs text-right">0.00</td>
                            @else
                            <td class="p-0 text-xs text-right">{{ ns()->currency->define( $order->vat_exempt_sales - $order->discount ) }}</td>
                            @endif
                        </tr>
                        <tr>
                            <td colspan="2" class="p-0  text-xs ">
                                <span>{{ __( 'Zero-Rated Sale' )  }}</span>
                            </td>
                            <td class="p-0 text-xs text-right">{{ ns()->currency->define( 0 ) }}</td>
                        </tr>
                        
                        <tr>
                            <td colspan="2" class="p-0 text-xs">
                                <span>{{ __( '12% VAT' ) /* __( 'Taxes' ) */ }}</span>
                            </td>
							
                            @if($order->discount_code == 'FREE')	
                                <td class="p-0 text-xs text-right ">0.00</td>
                            @else
                                @if($order->vat_exempt <= 0)
                                    <td class="p-0 text-xs text-right ">{{ ns()->currency->define( $order->total_tax_value ) }}</td>
                                @else
                                    @if($order->number_pax > 1)
                                        <td class="p-0 text-xs text-right ">{{ ns()->currency->define( $order->total_tax_value = ((($order->number_pax - $order->number_pax_discount)*((($order->subtotal - $order->sc_vatable) / $order->number_pax)+$order->sc_vatable))/1.12)*0.12 ) }}</td>
                                    <!-- <td class="p-0 text-xs text-right">{{ ns()->currency->define( $order->total_tax_value = ((($order->number_pax - $order->number_pax_discount)*($order->subtotal / $order->number_pax))/1.12)*0.12 ) }}</td> -->
                                    @else
                                        <td class="p-0 text-xs text-right ">{{ ns()->currency->define( ($order->sc_vatable / 1.12) * .12 ) }}</td>
                                    @endif        
                                @endif     
                            @endif
                        </tr>
                        {{-- @elseif ( $order->products_tax_value > 0 )
                        <tr>
                            <td colspan="2" class="p-0 text-xs ">
                            <span>{{ $order->tax_type === 'inclusive' ? __( 'Inclusive Product Taxes' ) : __( 'Exclusive Product Taxes' ) }}</span>
                            </td>
                            <td class="p-0">{{ ns()->currency->define( $order->products_tax_value ) }}</td>
                        </tr> --}}
                        @endif
                    @endif

                    <tr>
                        <td colspan="2" class="p-0 text-xs  border-b border-gray-800">
                            <span>{{ __( 'Vat Exempt' ) /* __( 'Taxes' ) */ }}</span>
                        </td>
                        <td class="p-0 text-xs text-right border-b border-gray-800">{{ ns()->currency->define( $order->vat_exempt_sales * 0.12 ) }}</td>
                    </tr>
                    @foreach( $order->payments as $payment )
                    <tr>
                        <td class="p-0 text-xs" colspan="2">{{ $paymentTypes[ $payment[ 'identifier' ] ] ?? __( 'Unknown Payment' ) }}
                            @if($payment['identifier'] != 'cash-payment')
                                Reference #: {{$payment[ 'reference' ] }}
                            @endif
                        </td>
                        <td class="p-0 text-xs text-right">{{ ns()->currency->define( $payment[ 'value' ] ) }}</td>
                    </tr>
                    @endforeach
                    {{-- <tr>
                        <td colspan="2" class="p-0 text-xs ">{{ __( 'Paid' ) }}</td>
                        <td class="p-0 text-xs text-right">{{ ns()->currency->define( $order->tendered ) }}</td>
                    </tr> --}}
                    @if ( in_array( $order->payment_status, [ 'refunded', 'partially_refunded' ]) )
                        @foreach( $order->refund as $refund )
                        <tr>
                            <td colspan="2" class="p-0 text-xs">{{ __( 'Refunded' ) }}</td>
                            <td class="p-0 text-xs text-right">{{ ns()->currency->define( - $refund->total ) }}</td>
                        </tr>
                        @endforeach
                    @endif
                    @switch( $order->payment_status )
                        @case( Order::PAYMENT_PAID )
                        <tr>
                            <td colspan="2" class="p-0 text-xs">{{ __( 'Change' ) }}</td>
                            <td class="p-0 text-xs text-right">{{ ns()->currency->define( $order->change ) }}</td>
                        </tr>
                        @break
                        @case( Order::PAYMENT_PARTIALLY )
                        <tr>
                            <td colspan="2" class="p-0  text-xs">{{ __( 'Change' ) }}</td>
                            <td class="p-0 text-xs text-right">{{ ns()->currency->define( abs( $order->change ) ) }}</td>
                        </tr>
                        @break
                    @endswitch
                </tbody>
            </table>
            @if( $order->note_visibility === 'visible' )
            <div class="pt-6 pb-4 text-center text-gray-800 text-xs">
                <strong>{{ __( 'Note: ' ) }}</strong> {{ $order->note }}
            </div>
            @endif
            <div class="pt-6 pb-4 text-center text-gray-800 text-xs">
                {{ ns()->option->get( 'ns_invoice_receipt_footer' ) }}
                <div class="text-xs font-bold">NOT VALID AS OFFICIAL RECEIPT</div>
                <div class="text-xs">Thank you for your patronage </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.print();
</script>