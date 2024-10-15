declare const popupResolver;
declare const popupCloser;
declare const __m;
declare const Popup;
declare const nsConfirmPopup;
declare const nsHttpClient;
declare const nsSnackBar;

export default {
    name: 'gastro-pos-order-move',
    template: `
    <div v-if="order" class="shadow-full ns-box w-95vw h-95vh md:w-3/4-screen lg:w-3/6-screen md:h-half overflow-hidden flex flex-col">
        <div class="border-b ns-box-header p-2 flex flex-col md:flex-row justify-between items-center">
            <div class="flex-auto">
                <h3 class="font-semibold mb-1 md:mb-0">{{ localization( 'Move Order', 'NsGastro' ) }}</h3>
            </div>
            <div class="flex items-center justify-between w-full md:w-auto">
                <div class="px-1">
                    <ns-close-button @click="closePopup()"></ns-close-button>
                </div>
            </div>
        </div>
        <div class="p-2 ns-box-body flex-auto flex flex-col overflow-hidden">
            <p class="text-center mb-4 text-primary">{{ 
                this.localization( \`You're about to move the order {order}. Please select the table where you would like to move the order.\`, 'NsGastro' )
                    .replace( '{order}', this.order.code )
            }}</p>
            <div class="shadow rounded elevation-surface flex flex-col overflow-hidden">
                <div class="p-2 flex-col flex border-b">
                    <div class="input-group border-2 overflow-hidden">
                        <input v-model="tableName" type="text" class="w-full p-2" :placeholder="this.localization( 'Search a table', 'NsGastro' )">
                    </div>
                </div>
                <div class="overflow-y-auto">
                    <ul>
                        <li @click="moveTo( table )" v-for="table of tables" :key="table.id" class="hover:bg-blue-100 cursor-pointer text-primary p-2 border-b flex justify-between">
                            <span>{{ table.name }}</span>
                            <div>
                                <span v-if="! table.busy" class="rounded-full px-2 text-xs py-1 bg-green-400">{{ localization( 'Available', 'NsGastro' ) }}</span>
                                <span v-if="table.busy" class="rounded-full px-2 text-xs py-1 bg-yellow-400">{{ localization( 'Busy', 'NsGastro' ) }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    `,
    props: [ 'popup' ],
    data() {
        return {
            order: null,
            tableName: '',
            watchTimeout: null,
            tables: []
        }
    },
    watch: {
        tableName() {
            clearTimeout( this.watchTimeout );
            this.watchTimeout   =   setTimeout( () => {
                this.searchTables( this.tableName );
            }, 1000 )
        }
    },
    methods: {
        popupResolver,
        popupCloser,
        localization: __m,

        closePopup() {
            this.popupResolver( false );
        },

        moveTo( table ) {
            Popup.show( nsConfirmPopup, {
                title: this.localization( `Move The Order To "{table}" ?`, 'NsGastro' ).replace( '{table}', table.name ),
                message: this.localization( 'The order will be moved to a new table. Would you like to confirm ? ', 'NsGastro' ),
                onAction: ( action ) => {
                    if ( action ) {
                        this.proceedMove( this.order, table );
                    }
                }
            })
        },

        proceedMove( order, table ) {
            nsHttpClient.post( `/api/gastro/orders/${order.id}/change-table`, { table_id : table.id })
                .subscribe( result => {
                    nsSnackBar.success( result.message, 'OK', { duration: 3000 }).subscribe();
                    this.popupResolver( true );
                }, ( error ) => {
                    const message   =   error.message || this.localization( 'An unexpected error occured while moving the order.', 'NsGastro' );
                    nsSnackBar  
                        .error( message, 'OK', { duration: 3000 })
                        .subscribe();
                });
        },

        searchTables( search ) {
            nsHttpClient.post( `/api/gastro/tables/search`, { search, ignore_table_id: this.order.table_id })
                .subscribe( tables => {
                    this.tables     =   tables;
                })
        }
    },
    mounted() {
        this.popupCloser();
        this.order      =   this.popup.params.order;
        this.searchTables();
    }
}