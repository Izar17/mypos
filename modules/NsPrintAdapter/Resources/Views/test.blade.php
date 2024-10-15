<{{'?xml version="1.0" encoding="UTF-8"?'}}>
    <configuration>
        <characterset>{{ $printer->characterset }}</characterset>
        <interface>Printer:{{ $printer->name }}</interface>
        <type>{{ $printer->type }}</type>
        <line-character>{{ $printer->line_character }}</line-character>
    </configuration>
    <document>
        <double-width size="3:3">
            <align mode="center">
                <bold>
                        <text-line>{{ __m( 'Cloud Test Print', 'NsPrintAdapter' ) }}</text-line>
                </bold>
            </align>
        </double-width>
        <line-feed/>
        <align mode="center">
            <text-line>{{ __m( 'This test confirm NexoPOS is able to communicate with Nexo Print Server from Cloud', 'NsPrintAdapter' ) }}</text-line> 
        </align>
        <align mode="center">
            <text-line>{{ __m( 'Additionnally this test is made to ensure every options offered by Nexo Print Server are supported by the current printer', 'NsPrintAdapter' ) }}</text-line>
        </align>
        <line-feed/>
        <invert>
            <bold>
                <text-line>Text Alignment</text-line>
            </bold>
        </invert>                            
        <align mode="left">
            <text-line size="1:2">Aligned Left</text-line>
        </align>
        <align mode="right">
            <text-line size="1:2">Aligned Right</text-line>
        </align>
        <align mode="center">
            <text-line size="1:2">Aligned Center</text-line>
        </align>
        <line-feed/>
        <invert>
            <bold>
                <text-line>Text Size & Weight</text-line>
            </bold>
        </invert>   
        <bold>
            <text-line>Bold Text</text-line>
        </bold>
        <quad-size>
            <text-line>Quart Size</text-line>
        </quad-size>
        <double-width>
            <text-line>Double Width</text-line>
        </double-width>
        <double-height>
            <text-line>Double Height</text-line>
        </double-height>
        <invert>
            <bold>
                <text-line>Image And Barcode</text-line>
            </bold>
        </invert>   
        <align mode="center">
            <image>https://user-images.githubusercontent.com/5265663/162700085-40ed00ca-9154-42cb-850a-ccf1c2db2d5d.png</image>
        </align>
        <line-separator/>
        <line-feed></line-feed>
        <full-cut/>
    </document>