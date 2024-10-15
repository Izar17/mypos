<?php
/**
 * Gastro - Restaurant Extension Model
 * @since 5.0.0
**/
namespace Modules\NsGastro\Models;

use App\Models\NsModel;

class TableAssignedWaiter extends NsModel
{
    protected $table = 'nexopos_gastro_tables_assigned_waiters';

    // crreate mass assignable fields
    protected $fillable = [
        'table_id',
        'user_id',
    ];
}