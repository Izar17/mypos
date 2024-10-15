<?php
use App\Classes\Hook;
?>
@inject( 'ordersService', 'App\Services\OrdersService' )
@extends( 'layout.base' )
@section( 'layout.base.body' )
<div id="print-wrapper">
    @include( Hook::filter( 'ns-web-receipt-template', 'pages.dashboard.orders.templates._receipt' ) )
</div>
<style>
    #print-wrapper td > span:first-child, #print-wrapper td {
        font-size: 1.3em !important;
    }
    #print-wrapper td > span:last-child {
        font-size: 1em !important;
    }
    #print-wrapper td:last-child {
        width: 120px;
    }
</style>
@endsection
