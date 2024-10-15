declare const __m;
declare const Popup;
declare const nsHttpClient;
declare const nsSnackBar;
declare const nsCurrency;

import gastroKeyboardVue from './gastro-keyboard';

export default {
    name: 'gastro-modifier-group',
    template: `
    <div class="shadow-lg ns-box h-95vh md:h-4/5-screen w-95vw md:w-3/5-screen flex flex-col">
        <div class="p-2 border-b ns-box-header flex justify-between items-center" v-if="modifierGroup !== null">
            <h3>{{ localization( 'Modifier:', 'NsGastro' ) }} {{ modifierGroup.name }}</h3>
            <ns-close-button @click="close()"></ns-close-button>
        </div>
        <div class="flex-auto flex items-center justify-center" v-if="modifierGroup === null">
            <ns-spinner></ns-spinner>
        </div>  
        <div class="overflow-hidden flex-auto flex flex-col" v-if="modifierGroup !== null">
            <div class="m-2 p-2 ns-notice success text-center">
                <p>{{ modifierGroup.description || 'No description provided.' }}</p>
            </div>
            <div class="flex-auto overflow-y-auto">
                <div class="go-grid go-grid-cols-4 go-flex-wrap">
                    <div @click="select( modifier )" :class="modifier.selected ? 'info' : ''" class="cursor-pointer border border-box-edge go-h-44 md:go-h-56" :key="modifier.id" v-for="modifier of modifierGroup.modifiers">
                        <div class="relative h-full w-full flex items-center justify-center overflow-hidden">
                            <div v-if="modifier.quantity > 0" class="flex items-center justify-center text-white absolute right-4 top-4 rounded-full h-8 w-8 bg-info-secondary font-bold">{{ modifier.quantity }}</div>
                            <img v-if="modifier.galleries[0]" :src="modifier.galleries[0].url" class="object-cover h-full" :alt="modifier.name">
                            <i class="las la-image text-secondary text-6xl" v-if="! modifier.galleries[0]"></i>
                        </div>
                        <div class="h-0 w-full">
                            <div class="relative w-full flex items-center justify-center -top-10 h-20 py-2 flex-col modifier-item">
                                <h3 class="font-bold text-primary py-2 text-center">{{ modifier.name }}</h3>
                                <span class="text-xs font-bold text-secondary py-1 text-center">{{ nsCurrency( modifier.unit_quantities[0].sale_price ) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t ns-box-footer border-gray p-2 flex justify-between items-center">
                <div></div>
                <div>
                    <ns-button @click="nextStep()" type="info">{{ localization( 'Continue', 'NsGastro' ) }}</ns-button>
                </div>
            </div>
        </div>      
    </div>
    `,
    props: [ 'popup' ],
    mounted() {
        this.loadModifierGroup();
    },
    data() {
        return {
            modifierGroup: null,
        }
    },
    methods: {
        localization: __m,
        nsCurrency,
        select( modifier ) {
            if ( ! this.modifierGroup.multiselect ) {
                const index     =   this.modifierGroup.modifiers.indexOf( modifier );
                
                this.modifierGroup.modifiers.forEach( ( _modifier, _index ) => {
                    if ( _index !== index ) {
                        _modifier.selected  =   false;
                        _modifier.quantity  =   0;
                    }
                });
            }

            modifier.selected   =   !modifier.selected;

            if ( this.modifierGroup.countable ) {
                if ( modifier.selected ) {
                    new Promise( async ( resolve, reject) => {
                        try {
                            modifier.quantity   =   1;
                            modifier    =   await Popup.show( gastroKeyboardVue, { resolve, reject, modifier, product: this.popup.params.product })
                        } catch( exception ) {
                            console.log( exception );
                            modifier.selected   =   false;
                        }
                    });
                } else {
                    modifier.quantity   =   0;
                }
            } else {
                if ( modifier.selected ) {
                    modifier.quantity   =   1;
                } else {
                    modifier.quantity   =   0;
                }
            }
        },
        loadModifierGroup() {
            nsHttpClient.get( `/api/gastro/modifiers-groups/${this.popup.params.modifierGroupId}` )
                .subscribe( result => {
                    result.modifiers            =   result.modifiers.map( modifier => {
                        modifier.modifier_id    =   modifier.id;
                        
                        /**
                         * we delete the id reference as it should point to the entries
                         * stored within the "nexopos_orders_products_modifiers".
                         */
                        delete modifier.id;

                        let reference           =   [];
                        if ( this.popup.params.product.modifiersGroups ) {
                            /**
                             * attempt to find if the group is already attached
                             * to the product so we can pull that.
                             */
                            const group     =   this.popup.params
                                .product
                                .modifiersGroups
                                .filter( _group => _group.modifier_group_id === this.popup.params.modifierGroupId );

                            /**
                             * We'll check fi the group length
                             */
                            if ( group.length > 0 ) {
                                reference   =   group[0].modifiers.filter( m => {
                                    return m.modifier_id === modifier.modifier_id;
                                })
                            }
                        }

                        modifier.selected   =   reference.length === 0 ? false : reference[0].selected;
                        modifier.quantity   =   reference.length === 0 ? 0 : reference[0].quantity;

                        return modifier;
                    });

                    this.modifierGroup  =   result;
                }, ( error ) => {
                    nsSnackBar.error( error.message || 'An unexpected error has occured.' )
                        .subscribe();
                })
        },
        nextStep() {
            const group         =   this.modifierGroup;

            /**
             * if the modifier is required
             * you need to select one before proceeding.
             */
            if ( this.modifierGroup.modifiers.filter( m => m.selected ).length === 0 && parseInt( group.forced ) === 1 ) {
                return nsSnackBar.error( 'You must select a modifier before proceeding.' ).subscribe();
            }

            /**
             * We need to specify quantity
             * for the provided modifier
             */
            if ( this.modifierGroup.modifiers.filter( m => m.selected ).length > 0 && parseInt( group.countable ) === 1 && parseInt( group.forced ) === 1 ) {
                const total     =   this.modifierGroup.modifiers.map( m => m.quantity )
                    .reduce( ( before, after ) => before + after );
                
                if ( total <= 0 ) {
                    return nsSnackBar.error( 'The current modifier group is require modifier with valid quantities.' ).subscribe();
                }
            }

            /**
             * make sure to only return
             * the modifiers that are selected.
             */
            group.modifier_group_id     =   group.id;
            group.modifiers             =   group.modifiers.filter( m => m.selected );
            group.modifiers.forEach( modifier => {
                modifier.unit_price         =   modifier.unit_quantities[0].sale_price;
                modifier.unit_quantity_id   =   modifier.unit_quantities[0].id;
                modifier.unit_id            =   modifier.unit_quantities[0].unit_id;
                modifier.total_price        =   modifier.unit_quantities[0].sale_price * modifier.quantity;
            });
            
            delete group.id;
            
            this.popup.params.resolve( group );
            this.popup.close();
        },
        close() {
            this.popup.params.reject( false );
            this.popup.close();
        }
    }
}