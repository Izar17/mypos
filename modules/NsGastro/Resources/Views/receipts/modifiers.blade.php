@inject( 'printService', 'Modules\NsPrintAdapter\Services\PrintService' )
@foreach( $modifiers as $modifier )
<text-line>
<?php echo $printService->nexting([
    '-> ' . $modifier->name . ' (x' . $modifier->quantity . ') - ' . ns()->currency->define( $modifier->total_price ),
    ''
]);
?></text-line>
@endforeach