<?php

namespace Modules\NsGastro\Models;

use App\Models\NsModel;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Factories\Relationship;

class Kitchen extends NsModel
{
    protected $table = 'nexopos_gastro_kitchens';

    const STATUS_ENABLED = 'enabled';
    const STATUS_DISABLED = 'disabled';

    /**
     * Relation for fetching attached
     * category.
     *
     * @return Relationship;
     */
    public function categories()
    {
        return $this->hasMany(KitchenCategory::class, 'kitchen_id');
    }

    /**
     * create relation with kitchen printers
     */
    public function printers()
    {
        return $this->hasMany(KitchenPrinter::class, 'kitchen_id');
    }

    /**
     * Filter kitchens by only getting kitchen
     * that are enabled.
     *
     * @param  QueryBuilder  $query
     * @return QueryBuilder
     */
    public function scopeEnabled($query)
    {
        return $query->where('status', self::STATUS_ENABLED);
    }

    /**
     * filter the kitchens by fetching only
     * disabled kitchens
     *
     * @param  QueryBuilder  $query
     * @return QueryBuilder
     */
    public function scopeDisabled($query)
    {
        return $query->where('status', self::STATUS_ENABLED);
    }
}
