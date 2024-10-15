<?php
use Illuminate\Support\Str;
?>
<script>
    const nsPrintData   =   {
        settings:   `{{ ns()->route( 'ns.print-adapter.settings' ) }}`
    }
</script>
@if ( ns()->option->get( 'ns_pa_cloud_print' ) === 'yes' )
@include( 'NsPrintAdapter::printers.cloud-print' )
@else
@include( 'NsPrintAdapter::printers.local-print' )
@endif