@inject( 'printerService', 'Modules\NsPrintAdapter\Services\PrintService' )
<{!! '?xml version="1.0" encoding="UTF-8"?' !!}>
<configuration>
    <characterset>{{ $printer->characterset }}</characterset>
    <interface>{{ $printerService->getPrinterInterface( $printer ) }}</interface>
    <type>{{ $printer->type ?? 'epson' }}</type>
    <line-character>{{ $printer->line_character ?? '*' }}</line-character>
</configuration>
<document>
    <web-url>{{ $url }}</web-url>
    <paper-cut></paper-cut>
</document>