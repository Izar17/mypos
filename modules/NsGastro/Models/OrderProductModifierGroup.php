<?php

namespace Modules\NsGastro\Models;

use App\Models\NsModel;

/**
 * @property int id
 * @property string name
 * @property int order_product_id
 * @property int modifier_group_id
 * @property bool multiselect
 * @property bool countable
 * @property bool forced
 */
class OrderProductModifierGroup extends NsModel
{
    protected $table = 'nexopos_orders_products_modifiers_groups';

    public function modifiers()
    {
        return $this->hasMany(OrderProductModifier::class, 'order_product_modifier_group_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(ModifierGroup::class, 'modifier_group_id', 'id');
    }

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class, 'order_product_id', 'id');
    }
}
