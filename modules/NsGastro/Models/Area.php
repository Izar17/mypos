<?php

namespace Modules\NsGastro\Models;

use App\Models\NsModel;

class Area extends NsModel
{
    protected $table = 'nexopos_gastro_areas';

    public function tables()
    {
        return $this->hasMany(Table::class, 'area_id', 'id');
    }
}
