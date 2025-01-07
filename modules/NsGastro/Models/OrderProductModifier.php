<?php

namespace Modules\NsGastro\Models;

use App\Models\NsModel;
use App\Models\Product;

/**
 * @property int id
 * @property int order_product_id
 * @property int modifier_id
 * @property string name
 * @property int order_product_modifier_group_id
 * @property float unit_price
 * @property int unit_id
 * @property float quantity
 * @property float total_price
 * @property float tax_value
 */
class OrderProductModifier extends NsModel
{
    protected $table = 'nexopos_orders_products_modifiers';

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'modifier_id');
    }

    public function group()
    {
        return $this->belongsTo(OrderProductModifierGroup::class, 'order_product_modifier_group_id', 'id');
    }
}
