<{!! '?xml version="1.0" encoding="UTF-8"?' !!}>
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
    <align mode="center">
        <text-line>No supported Document</text-line>
    </align>
</document>