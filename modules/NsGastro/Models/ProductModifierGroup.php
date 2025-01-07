<?php
/**
 * Gastro - Restaurant Extension Model
 * @since 5.0.0
**/
namespace Modules\NsGastro\Models;

use App\Models\NsModel;
use App\Models\Product;

class ProductModifierGroup extends NsModel
{
    protected $table = 'nexopos_gastro_products_modifiers_groups';

    protected $fillable = [
        'product_id',
        'modifier_group_id',
        'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo( Product::class, 'product_id' );
    }

    public function modifierGroup()
    {
        return $this->belongsTo( ModifierGroup::class, 'modifier_group_id' );
    }
}