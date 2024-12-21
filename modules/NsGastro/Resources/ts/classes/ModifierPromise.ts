declare const Popup;

import gastroModifierGroup from '../components/gastro-modifier-group';

export class ModifierPromise {
    constructor( protected product ) {}

    run( queueData ) {

        return new Promise( async ( resolve, reject ) => {
            const product   =   this.product;

            if ( product.$original().gastro_item_type === 'product' && product.$original().modifiers_groups.length > 0 ) {
                const modifiers         =   JSON.parse( product.$original().modifiers_groups );
                const modifiersGroups    =   new Array;

                for( let index in modifiers ) {
                    try {
                        modifiersGroups.push( await this.loadModifier( modifiers[index], product ) );
                    } catch( exception ) {
                        return reject( exception );
                    }
                }

                return resolve({ modifiersGroups });
            }

            return resolve({});
        });
    }

    loadModifier( id, product ) {
        return new Promise( ( resolve, reject ) => {
            Popup.show( gastroModifierGroup, { resolve, reject, product, modifierGroupId : id });
        })
    }

    /**
     * compute product by mutating the product price value
     * @deprecated
     * @param modifiersGroups modifiers groups
     * @param queueData product data
     */
    computeProduct( modifiersGroups, queueData ) {
        const finalObject   =   new Object;

        [
            'excl_tax_sale_price',
            'excl_tax_wholesale_price',
            'incl_tax_sale_price',
            'incl_tax_wholesale_price',
            'sale_price',
            'sale_price_edit',
            'sale_price_tax',
            'wholesale_price',
            'wholesale_price_edit',
            'wholesale_price_tax',
        ].forEach( item => {
            finalObject[ item ]     =   Object.values( modifiersGroups )
                .map( (group: any) => {
                    return group.modifiers.map( modifier => {
                        return modifier.unit_quantities[0][ item ] * modifier.quantity
                    });
                })
                .flat()
                .reduce( ( before, after ) => before + after ) + queueData.$quantities()[ item ];
        })

        /**
         * We've updated the prices as the modifier
         * has been added to the product.
         */
        const $quantities    =   () => {
            return { 
                ...queueData.$quantities(),
                ...finalObject
            }
        };

        return $quantities;
    }
}