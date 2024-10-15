<?php

namespace Modules\NsPrintAdapter\Events;

class NsPrintAdapterFilter
{
    public static function customKitchenPrint( $string )
    {
        if ( ns()->option->get( 'ns_pa_convert_to_image', 'no' ) === 'yes' ) {
            return 'NsPrintAdapter::receipt.images.kitchen-receipt';
        }

        return $string;
    }

    public static function customCanceledKitchenPrint( $string )
    {
        if ( ns()->option->get( 'ns_pa_convert_to_image', 'no' ) === 'yes' ) {
            return 'NsPrintAdapter::receipt.images.canceled-receipt';
        }

        return $string;
    }
}
