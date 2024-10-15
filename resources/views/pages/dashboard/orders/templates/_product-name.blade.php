<span class="text-xs">{{ $product->quantity }} {{ $product->name }}  {{ ns()->currency->define( $product->unit_price ) }}</span>
<br>
<span class="text-xs text-gray-600">{{ $product->unit->name }}</span>