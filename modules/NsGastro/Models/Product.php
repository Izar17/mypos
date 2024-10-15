<?php 
namespace Modules\NsGastro\Models;

use App\Models\Product as ModelsProduct;

class Product extends ModelsProduct
{
    public function modifiersGroups()
    {
        return $this->belongsToMany( ModifierGroup::class, 'nexopos_gastro_products_modifiers_groups', 'product_id', 'modifier_group_id' );
    }
}