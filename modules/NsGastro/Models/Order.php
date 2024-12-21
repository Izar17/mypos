<?php

namespace Modules\NsGastro\Models;

use App\Models\Order as ModelsOrder;

class Order extends ModelsOrder
{
    const COOKING_PENDING = 'pending';

    const COOKING_ONGOING = 'ongoing';

    const COOKING_READY = 'ready';

    const COOKING_REQUESTED = 'requested';

    const COOKING_PROCESSED = 'processed';

    const COOKING_SERVED = 'served';

    const TYPE_DINEIN = 'dine-in';

    public function products()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }

    public function scopeDaily($query)
    {
        return $query
            ->where('created_at', '>=', ns()->date->copy()->startOfDay())
            ->where('created_at', '<=', ns()->date->copy()->endOfDay());
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id');
    }
}
