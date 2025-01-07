<?php

namespace Modules\NsGastro\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Modules\NsGastro\Models\ModifierGroup;
use Modules\NsGastro\Models\ProductModifierGroup;

class ProductModifiersService
{
    public function createGroup($data)
    {
        $group = new ModifierGroup;
        $group->name = $data['name'];
        $group->forced = $data['forced'];
        $group->countable = $data['countable'];
        $group->multiselect = $data['multiselect'];
        $group->description = $data['description'];
        $group->author = Auth::id();
        $group->save();

        return [
            'status'    =>  'success',
            'message'   =>  __m('The product modifier group has been create.', 'NsGastro'),
        ];
    }

    /**
     * @todo check if used.
     */
    public function setProductModifierGroup( Product $product )
    {
        ProductModifierGroup::where( 'product_id', $product->id )->delete();

        $groups     =   json_decode( $product->modifiers_groups );

        if ( ! empty( $groups ) ) {
            foreach( $groups as $group ) {
                $modifierGroup  =   new ProductModifierGroup;
                $modifierGroup->product_id  =   $product->id;
                $modifierGroup->modifier_group_id  =   $group;
                $modifierGroup->save();
            }
        }
    }
}
