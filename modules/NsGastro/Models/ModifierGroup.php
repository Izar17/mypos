<?php

namespace Modules\NsGastro\Models;

use App\Models\NsModel;
use App\Models\Product;
use Modules\NsGastro\Events\ModifierGroupAfterCreatedEvent;
use Modules\NsGastro\Events\ModifierGroupAfterUpdatedEvent;
use Modules\NsGastro\Events\ModifierGroupBeforeDeletedEvent;

/**
 * @property string name
 * @property string description
 * @property bool forced
 * @property bool multiselect
 * @property bool countable
 * @property int author
 */
class ModifierGroup extends NsModel
{
    protected $table = 'nexopos_gastro_modifiers_groups';

    protected $dispatchesEvents = [
        'updated'   => ModifierGroupAfterUpdatedEvent::class,
        'created'   => ModifierGroupAfterCreatedEvent::class,
        'deleting'  => ModifierGroupBeforeDeletedEvent::class,
    ];

    public function modifiers()
    {
        return $this->hasMany(Product::class, 'modifiers_group_id');
    }
}
