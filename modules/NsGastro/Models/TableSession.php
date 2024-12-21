<?php

namespace Modules\NsGastro\Models;

use App\Models\NsModel;

class TableSession extends NsModel
{
    protected $table = 'nexopos_gastro_tables_sessions';

    public $timestamps = false;

    public function scopeActive($query, $status = true)
    {
        return $query->where('active', $status);
    }

    public function scopeTable($query, $table_id)
    {
        return $query->where('table_id', $table_id);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'gastro_table_session_id', 'id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_id', 'id');
    }
}
