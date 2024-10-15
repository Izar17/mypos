<?php
/**
 * NPS Adapter Model
 *
 * @since  4.7.0
**/

namespace Modules\NsPrintAdapter\Models;

use App\Models\NsModel;
use Modules\NsPrintAdapter\Events\PrinterAfterCreatedEvent;
use Modules\NsPrintAdapter\Events\PrinterAfterUpdatedEvent;

class Printer extends NsModel
{
    const DISABLED = 'disabled';

    const ENABLED = 'enabled';

    const INTERFACE_ETHERNET = 'ethernet';

    const INTERFACE_USBSERIAL = 'usb_serial';

    const INTERFACE_SERIAL = 'serial';

    const INTERFACE_PARALLEL = 'parallel';

    protected $fillable     =   [
        'name', 'identifier', 'is_default'
    ];

    protected $casts    =   [
        'is_default'    =>  'boolean',
    ];

    protected $dispatchesEvents     =   [
        'updated'   =>  PrinterAfterUpdatedEvent::class,
        'created'   =>  PrinterAfterCreatedEvent::class,
    ];

    protected $table = 'nexopos_printers';

    public function scopeEnabled($query)
    {
        return $query->where('status', self::ENABLED);
    }

    public function scopeIsDefault( $query ) 
    {
        return $query->where( 'is_default', true );
    }
}
