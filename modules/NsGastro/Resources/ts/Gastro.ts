import { ModifierPromise } from "./classes/ModifierPromise";
import { SendToKitchenQueue } from "./classes/SendToKitchenQueue";


import gastroAddButtons from './components/gastro-add-buttons.js';
import gastroPosMeal from './components/gastro-pos-meal';
import gastroSplitOrder from "./components/gastro-split-order";
import gastroTable from './components/gastro-table';
import gastroToKitchenButtonComponent from './components/gastro-to-kitchen-button';
import './scss/gastro.scss';

declare const Popup;
declare const POS;
declare const nsEvent;
declare const Vue;
declare const nsSnackBar;
declare const nsHooks;
declare const GastroSettings;
declare const nsConfirmPopup;
declare const RxJS;
declare const __;
declare const ns;
declare const __m;
declare const nsHttpClient;
declare const nsExtraComponents;
declare const markRaw;
declare const nsCurrency;
declare const defineAsyncComponent;
declare const nsSelectPopup;

class Gastro {
    addButtonsVisible           =   new RxJS.ReplaySubject();
    tableOpenedSubject          =   new RxJS.ReplaySubject();
    selectedOrdersSubject       =   new RxJS.BehaviorSubject([]);
    tableOpenedStatus           =   false;
    defaultCartButtons          =   [];
    currentScreen : 'both' | 'cart' | 'grid';

    /**
     * this keeps the instance
     * of the view app.
     */
    addToOrderButton            =   null;

    selectedOrder   =   new RxJS.BehaviorSubject();

    getType() {
        return {
            'identifier'    :   'dine-in',
            'label'         :   `Dine in ${(() => {
                const order     =   POS.order.getValue();

                if ( order.table ) {
                    // return order.table.name + `${ order.table.selectedSeats > 0 ? ` (${order.table.selectedSeats})` : '' }`;
                }
    
                return '';
            })()}`,
            'icon'          :   GastroSettings.icons.chair,
            'selected'      :   false
        }
    };

    constructor() {
        nsHooks.addAction( 'ns-pos-pending-orders-refreshed', 'gastro-add-controls', ( orderLines ) => {
            orderLines.forEach( line => {
                if ( line.dom && ! line.dom.querySelector( '.gastro-controls' ) ) {
                    const button    =   document.createElement( 'button' );
                    button.setAttribute( 'class', 'gastro-controls info px-2' );
                    button.innerHTML = `<i class="las la-cog"></i> ${__m( 'More', 'NsGastro' )}`;
                    button.addEventListener( 'click', async () => {
                        const promise   =   await new Promise( ( resolve, reject ) => {
                            Popup.show( nsSelectPopup, {
                                label: __m( 'Order Options', 'NsGastro' ),
                                description: __m( 'Select an option to apply to this order.', 'NsGastro' ),
                                options: [{
                                    label: __m( 'Split Order', 'NsGastro' ),
                                    value: 'split',
                                }, {
                                    label: __m( 'Select For Merge', 'NsGastro' ),
                                    value: 'merge',
                                }],
                                resolve,
                                reject
                            })
                        });

                        switch( promise ) {
                            case 'split': Popup.show( gastroSplitOrder, { order: line.order }); break;
                            case 'merge': this.selectOrderForMerging( line.order ) ; break;
                        }
                    });
    
                    line.dom.querySelector( '.ns-buttons' ).appendChild( button );
                }
            });
        });
        nsHooks.addAction( 'ns-pos-header', 'gastro-add-table-button', ( header ) => this.addHeaderButton( header ) );
        nsHooks.addAction( 'ns-after-product-computed', 'gastro-update-product', ( product ) => this.computeProduct( product ) );
        nsHooks.addAction( 'ns-cart-after-refreshed', 'gastro-build-modifier', ( order ) => setTimeout( () => this.buildModifierVue( order ), 100 ) );
        nsHooks.addAction( 'ns-before-load-order', 'gastro-catch-order', ( order ) => this.retrictOrderEdition() );
        nsHooks.addFilter( 'ns-pending-orders-right-column', 'gastro-right-column', ( lines ) => {
            lines.push({
                label: __m( 'Table Name', 'Gastro' ),
                value: ( order ) => order.table_name || __m( 'N/A', 'Gastro' )
            });

            return lines;
        });

        this.tableOpenedSubject.subscribe( status => this.tableOpenedStatus = status );
        this.addButtonsVisible.subscribe( status => {
            if ( status ) {
                POS.cartButtons.next([]);
                const buttons   =   {};
                buttons[ 'nsGastroAddButtons' ]     =   gastroAddButtons;
                POS.cartButtons.next( buttons );
            } else {
                POS.cartButtons.next( this.defaultCartButtons );
            }
        });

        nsExtraComponents[ 'nsGastroTable' ]    =   gastroTable;
    }

    selectOrderForMerging( order ) {
        return new Promise( ( resolve, reject ) => {
            const selectedOrders    =   this.selectedOrdersSubject.getValue();
            const exists            =   selectedOrders.filter( __order => __order.code === order.code ).length > 0;

            if ( ! exists ) {
                selectedOrders.push( order );
                this.selectedOrdersSubject.next( selectedOrders );
                nsSnackBar.success( __m( 'The order "{orderCode}" is selected.', 'NsGastro' ).replace( '{orderCode}', order.code ), __m( 'Close', 'NsGastro' ) ).subscribe();
                resolve(true);
            } else {
                nsSnackBar.error( __m( 'The order is already selected.', 'NsGastro' ), __m( 'Close', 'NsGastro' ) ).subscribe();
                reject( false );
            }
        });
    }

    retrictOrderEdition() {
        if ( ! GastroSettings.permissions.gastroEditOrder && ! this.tableOpenedStatus ) {
            nsSnackBar.error( __( 'You\'re not allowed to edit orders.' ) ).subscribe();
            throw 'Not allowed';
        }
    }

    printOrderCanceledMealKitchen( order_id, products_id = [] ) {
        if ( ! GastroSettings.ns_gastro_allow_cancelation_print ) {
            return false;
        }
        
        const result   =   nsHooks.applyFilters( 'ns-gastro-print-order-canceled-meal', ({ status: 'error', message: __m( 'No Print Handler for canceled meals', 'NsGastro' ), data: {
            order_id, products_id
        } }));

        if ( result.status === 'error' ) {
            nsSnackBar.error( result.message ).subscribe();
        }
    }

    setAddButtonsVisibility( status : 'visible' | 'hidden' ) {
        if ( status === 'visible' ) {
            this.addButtonsVisible.next( true );
        } else {
            this.addButtonsVisible.next( false );
        }
    }

    boot() {
        this.bindPromise();
        this.registerCustomOrderType();
        this.injectSendToKitchenPopup();

        nsHooks.addAction( 'ns-after-cart-reset', 'ns-gastro-cart-buttons', () => {
            this.removeHoldButton();
            this.addToKitchenButton();
            this.selectedOrdersSubject.next([]);

            // we store default buttons for a quick restoration.
            this.defaultCartButtons     =   POS.cartButtons.getValue();
        }, 20 ); // this should execute after core hooks
    }

    removeHoldButton() {
        const buttons   =   POS.cartButtons.getValue();
        delete buttons[ 'nsPosHoldButton' ];
        POS.cartButtons.next( buttons );
    }

    addToKitchenButton() {
        const buttons       =   POS.cartButtons.getValue();
        const newButtons    =   ns.insertAfterKey( buttons, 'nsPosPayButton', 'nsGastroToKitchen', markRaw( gastroToKitchenButtonComponent ) );
        POS.cartButtons.next( newButtons );
    }

    injectAddToOrderButtons() {
        // @todo gastroAddButtons
    }

    bindPromise() {
        POS.addToCartQueue[ 'ModifierPromise' ]   =   ModifierPromise;
    }

    /**
     * Add a custom table management
     * button to the header buttons.
     * @param header Object
     */
    addHeaderButton( header ) {
        if ( GastroSettings.ns_pos_order_types ) {
            const dineInOrderTypeSelected =    GastroSettings.ns_pos_order_types.filter( type => type === 'dine-in' ).length > 0;
    
            if ( dineInOrderTypeSelected ) {
                header.buttons[ 'GastroTableButton' ]       =   defineAsyncComponent( () => import( './components/gastro-table-button' ) );
            }
        }

        header.buttons[ 'GastroOrdersButton' ]      =   defineAsyncComponent( () => import( './components/gastro-pos-orders-button' ) );
        header.buttons[ 'GastroSplitOrderButton' ]  =   defineAsyncComponent( () => import( './components/gastro-split-orders-button' ) );
        header.buttons[ 'GastroMergeOrderButton' ]  =   defineAsyncComponent( () => import( './components/gastro-merge-orders-button' ) );

        return header;
    }

    registerCustomOrderType() {
        const types     =   POS.types.getValue();

        const dineInOrderTypeSelected =    GastroSettings.ns_pos_order_types.filter( type => type === 'dine-in' ).length > 0;

        if ( ! dineInOrderTypeSelected ) {
            return false;
        }

        POS.orderTypeQueue.push({
            identifier: 'gastro.table',
            promise: async ( selectedType ) => {
                return await new Promise( ( resolve, reject ) => {
                    if ( selectedType.identifier === 'dine-in' ) {
                        Popup.show( gastroTable, { resolve, reject });
                    } else {
                        resolve( true );
                    }
                })
            }
        })
    }

    computeProduct( product ) {
        if ( product.modifiersGroups !== undefined && product.modifiersGroups.length > 0 ) {
            /**
             * this will compute the total of each modifier
             * and additionnate with the actual product total.
             */
            let additionalPrice     =   0;

            if ( product.modifiersGroups.length > 0 ) {
                product.modifiersGroups.forEach( group => {
                    group.modifiers.forEach( modifier => {
                        additionalPrice     +=  modifier.total_price;
                    });
                })
            }

            product.modifiers_total         =   additionalPrice * product.quantity;
            product.modifiers_net_total     =   additionalPrice * product.quantity;
            product.modifiers_gross_total   =   additionalPrice * product.quantity;
            product.total_price             =   ( ( product.unit_price + additionalPrice ) * product.quantity );
            product.total_price_with_tax    =   ( ( product.unit_price + additionalPrice ) * product.quantity );
            product.total_price_without_tax =   ( ( product.unit_price + additionalPrice ) * product.quantity );
        }
    }

    buildModifierVue( order ) {
        order.products.forEach( ( product, index ) => {
            const productLineDom        =   document.querySelector( `[product-index="${index}"]` );

            /**
             * in case the cart is not visible
             * we should't proceed.
             */
            if ( productLineDom === null ) {
                return false;
            }

            /**
             * if the modifier group has been
             * previously added, we'll remove that
             */
            if ( productLineDom.querySelector( '.modifier-container' ) !== null ) {
                productLineDom.querySelector( '.modifier-container' ).remove();
            }

            this.injectModifiersGroups( product, index );
            this.injectCutleryOptions( product, index );
        });
    }

    /**
     * replaces the "Hold" button into a "To Kitchen" button.
     * Gives the choice to hold the button once pressed.
     */
    injectSendToKitchenPopup() {
        nsHooks.addFilter( 'ns-hold-queue', 'gastro-inject-send-to-kitchen', ( queues ) => {
            queues.push( SendToKitchenQueue );
            return queues;
        });
    }

    injectModifiersGroups( product, index ) {
        if ( product.modifiersGroups && product.modifiersGroups.length > 0 ) {
            const productLineDom        =   document.querySelector( `[product-index="${index}"]` );

            /**
             * Let's create a new wrapper and
             * append it to the product details container.
             */
            const modifierContainer     =   document.createElement( 'div' );
            modifierContainer.className =   'modifier-container mt-2 text-sm cursor-pointer';
            modifierContainer.setAttribute( 'product-reference', index );   
            productLineDom.querySelector( 'div' ).appendChild( modifierContainer );

            /**
             * Let's loop modifiers
             * and make sure to add them to modifier container.
             */
            product.modifiersGroups.forEach( (group: any) => {
                group.modifiers.forEach( modifier => {
                    const modifierTemplate  =   document.createElement( 'template' );
                    const html              =   `
                    <div class="single-modifier p-1 flex justify-between">
                        <span>${group.name} : ${modifier.name} (x${modifier.quantity})</span>
                        <div class="flex">
                            <span>${nsCurrency(modifier.total_price)}</span>
                            <ns-close-button></ns-close-button>
                        </div>
                    </div>
                    `
                    modifierTemplate.innerHTML  =   html.trim();
                    productLineDom.querySelector( '.modifier-container' ).appendChild( modifierTemplate.content.firstChild )
                })
            });

            modifierContainer.addEventListener( 'click', async function() {
                const productIndex  =   this.getAttribute( 'product-reference' );
                let product         =   POS.order.getValue().products[ productIndex ];
                
                try {
                    const modifierPromise       =   new ModifierPromise( product );
                    const response              =   <any>( await (  modifierPromise.run( product ) ) );
                    const data                  =   { ...product, ...response };

                    POS.updateProduct( product, data, productIndex );

                } catch( exception ) {
                    console.log( exception );
                }
            });
        }
    }

    injectCutleryOptions( product, index ) {
        const productLineDom        =   document.querySelector( `[product-index="${index}"]` );

        product     =   POS.products.getValue()[ index ];

        if ( productLineDom.querySelectorAll( '.cutlery-options' ).length === 0 ) {
            const modifierTemplate      =   document.createElement( 'template' );
            const html                  =   `
                <div class="px-1 cutlery-options">
                    <a class="hover:text-blue-600 cursor-pointer outline-none border-dashed py-1 border-b  text-sm border-blue-400">
                        <i class="las la-utensils text-xl"></i>
                    </a>
                </div>
            `
            modifierTemplate.innerHTML  =   html.trim();
            productLineDom.querySelector( '.product-options' ).appendChild( modifierTemplate.content.firstChild );

            /**
             * add an events listener to cutlery icon
             * to display meals options.
             */
            productLineDom.querySelector( '.cutlery-options a' ).addEventListener( 'click', function() {
                product     =   POS.products.getValue()[ index ];

                new Promise( ( resolve, reject ) => {
                    Popup.show( gastroPosMeal, { resolve, reject, product })
                }).then( response => {
                    console.log( response );
                }).catch( error => console.log( error ) );
            });
        }
    }
}

/**
 * when the DOM is ready
 * to be loaded.
 */
window[ 'Gastro' ]   =   new Gastro;
document.addEventListener( 'DOMContentLoaded', () => {
    window[ 'Gastro' ].boot();
});