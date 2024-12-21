@if( ! empty( $modifiers ) )
<ul>
    @foreach( $modifiers as $modifier )
    <li>{{ $modifier->name }}(x{{ $modifier->quantity}}) &mdash; {{ ns()->currency->define( $modifier->total_price ) }}</li>
    @endforeach
</ul>
@endif