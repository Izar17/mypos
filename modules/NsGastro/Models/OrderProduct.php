<?php

namespace Modules\NsGastro\Models;

use App\Models\OrderProduct as ModelsOrderProduct;

class OrderProduct extends ModelsOrderProduct
{
    const COOKING_PENDING = 'pending';

    const COOKING_ONGOING = 'ongoing';

    const COOKING_CANCELED = 'canceled';

    const COOKING_READY = 'ready';

    const COOKING_SERVED = 'served';

    const COOKING_REQUESTED = 'requested';

    const COOKING_PROCESSED = 'processed';
    
    public $fillable    =   [
        'cooking_status'
    ];

    public function modifiers()
    {
        return $this->hasMany(OrderProductModifier::class, 'order_product_id', 'id');
    }

    public function scopeCookingStatus($query, $status)
    {
        return $query->where('cooking_status', $status);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
