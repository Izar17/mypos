<?php

namespace Modules\NsGastro\Models;

use App\Models\NsModel;
use App\Models\ProductCategory;
use Modules\NsPrintAdapter\Models\Printer;

class KitchenCategory extends NsModel
{
    protected $table = 'nexopos_gastro_kitchens_categories';

    /**
     * @deprecated
     */
    public function categories()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id', 'id');
    }

    public function kitchen()
    {
        return $this->belongsTo(Kitchen::class, 'kitchen_id', 'id');
    }

    public function printers()
    {
        return $this->hasManyThrough(
            related: Printer::class,
            through: KitchenPrinter::class,
            firstKey: 'kitchen_id',
            secondKey: 'id',
            secondLocalKey: 'printer_id',
            localKey: 'kitchen_id'
        );
    }
}
