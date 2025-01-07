<?php

namespace Modules\NsGastro\Models;

use App\Models\NsModel;
use Illuminate\Database\Eloquent\Factories\Relationship;
use Modules\NsPrintAdapter\Models\Printer;

/**
 * KitchenPrinter Model
 *
 * @package Modules\NsGastro\Models
 * @property int $id
 * @property int $kitchen_id
 * @property int $printer_id
 * @property Printer $printer
 * @property Kitchen $kitchen
 */
class KitchenPrinter extends NsModel
{
    protected $table = 'nexopos_gastro_kitchens_printers';

    /**
     * Relation for fetching attached
     * kitchen.
     *
     * @return Relationship;
     */
    public function kitchen()
    {
        return $this->belongsTo(Kitchen::class, 'kitchen_id');
    }

    public function printer()
    {
        return $this->hasOne( Printer::class, 'id', 'printer_id' );
    }
}
