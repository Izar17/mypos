declare const __m;
declare const popupCloser;
declare const popupResolver;
declare const nsHttpClient;
declare const nsSnackBar;

export default {
    name: 'gastro-kitchen-requested-orders',
    template: `
    <div class="shadow-lg flex flex-col ns-box w-95vw h-95vh md:w-3/5-screen md:h-4/5-screen">
        <div class="ns-box-header border-b popup-heading">
            <h3>{{ localization( 'Requested Orders', 'NsGastro' ) }}</h3>
            <div>
                <ns-close-button @click="popupResolver( false )"></ns-close-button>
            </div>
        </div>
        <div v-if="orders.length === 0" class="flex items-center justify-center flex-auto ns-box-body">
            <div class="flex flex-col items-center justify-center">
                <i class="go-text-9xl las la-frown"></i>
                <div class="flex flex-col items-center">
                    <h3 class="font-semibold text-lg">{{ localization( 'No requested order has been found.', 'NsGastro' ) }}</h3>
                    <span class="text-sm">{{ localization( 'No order has been requested to be served.', 'NsGastro' ) }}</span>
                </div>
            </div>
        </div>
        <div class="p-2" v-if="orders.length > 0">
            <ul>
                <li v-for="order of orders" :key="order.id" class="border-b-2 border-blue-400 p-2 flex justify-between">
                    <div>
                        <h3 class="font-semibold">{{ order.order_code }} &mdash; {{ order.order_title || this.localization( 'Unnamed Order', 'NsGastro' ) }}</h3>
                        <div class="-mx-2 flex flex-wrap">
                            <div class="px-2 text-sm text-gray-600">
                                {{ localization( 'Table', 'NsGastro' ) }} : {{ order.table_name || this.localization( 'N/A', 'NsGastro' ) }}
                            </div>
                        </div>
                    </div>
                    <div>
                        <button v-if="order.gastro_order_status === 'requested'" @click="setOrderAsProcessed( order )" class="bg-green-400 text-white rounded-full px-4 py-2">{{ localization( 'Okay', 'NsGastro' ) }}</button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    `,
    data() {
        return {
            orders: []
        }
    },
    props: [ 'popup' ],
    methods: {
        localization: __m,
        popupCloser,
        popupResolver,
        orders: [],

        setOrderAsProcessed( order ) {
            nsHttpClient.post( `/api/gastro/orders/${order.order_id}/cooking-status`, {
                status: 'processed'
            }).subscribe( result => {
                this.loadRequestedOrders();
                return nsSnackBar
                    .success( result.message, this.localization( 'Okay', 'NsGastro' ), { duration: 3000 })
                    .subscribe();
            })
        },

        loadRequestedOrders() {
            nsHttpClient.post( `/api/gastro/kitchens/${this.popup.params.kitchen.id}/orders`, {
                    'cooking_status' : [ 'requested' ]
                })
                .subscribe( orders => {
                    this.orders     =   orders;
                })
        }
    },
    mounted() {
        this.loadRequestedOrders();
    },
}