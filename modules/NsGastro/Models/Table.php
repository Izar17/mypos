<?php

namespace Modules\NsGastro\Models;

use App\Models\NsModel;

class Table extends NsModel
{
    protected $table = 'nexopos_gastro_tables';

    protected $casts = [
        'busy'  =>  'boolean',
    ];

    const STATUS_AVAILABLE = 'available';

    const STATUS_UNAVAIABLE = 'unavailable';

    public function assignedWaiters()
    {
        return $this->hasMany(TableAssignedWaiter::class, 'table_id', 'id');
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeBusy($query, $occupationStatus = true)
    {
        return $query->where('busy', $occupationStatus);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'table_id', 'id');
    }

    public function sessions()
    {
        return $this->hasMany(TableSession::class, 'table_id', 'id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id', 'id');
    }
}
