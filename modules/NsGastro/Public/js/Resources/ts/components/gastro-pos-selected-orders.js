import gastroPosMerge from "./gastro-pos-merge";
export default {
    template: `
    <div class="w-95vw h-95vh md:w-3/5-screen md:h-3/5-screen bg-white shadow-lg flex flex-col">
        <div class="header border-b border-gray-200 p-2 flex justify-between items-center">
            <h3 class="font-bold">{{ __m( 'Selected Orders', 'NsGastro' ) }}</h3>
            <div>
                <ns-close-button @click="closePopup()"></ns-close-button>
            </div>
        </div>
        <template  v-if="orders.length > 0">
            <div class="body flex-auto">
                <div v-for="order of orders" class="box-shadow border-b p-2 flex justify-between items-center mb-2">
                    <div>
                        <h3 class="font-bold">{{ order.code }}</h3>
                        <div>
                            <small>{{ __m( 'Table:', 'NsGastro' ) }} {{ order.table_name }}</small> | 
                            <small>{{ __m( 'Customer:', 'NsGastro' ) }} {{ order.customer ? order.customer.first_name : order.customer_first_name }} {{ order.customer ? order.customer.last_name : order.customer_last_name }}</small>
                        </div>
                    </div>
                    <div>
                        <ns-close-button @click="removeFromSelected(order)"></ns-close-button>
                    </div>
                </div>
            </div>
            <div class="footer p-2 -mx-2 flex justify-end border-t items-center">
                <div class="px-2">
                    <ns-button type="info" @click="cancelSelection()">{{ __m( 'Cancel' ) }}</ns-button>
                </div>
                <div class="px-2">
                    <ns-button type="success" @click="mergeSelectedOrders()">{{ __m( 'Merge Orders' ) }}</ns-button>
                </div>
            </div>
        </template>
        <div class="body flex-auto flex items-center justify-center" v-else>
            <div class="p-2">
                <div class="text-center">
                    <i class="las la-frown text-3xl"></i>
                    <h3 class="font-bold">{{ __m( 'No selected orders', 'NsGastro' ) }}</h3>
                </div>
            </div>
        </div>
    </div>
    `,
    data() {
        return {
            orders: [],
            selectedOrderSubscription: null,
        };
    },
    mounted() {
        this.selectedOrderSubscription = Gastro.selectedOrdersSubject.subscribe(orders => {
            this.orders = orders;
            this.$forceUpdate();
        });
    },
    beforeDestroy() {
        this.selectedOrderSubscription.unsubscribe();
    },
    props: ['popup'],
    methods: {
        __m,
        popupCloser,
        popupResolver,
        closePopup() {
            this.popupResolver(false);
        },
        removeFromSelected(order) {
            const orders = this.orders.filter(o => o.id !== order.id);
            Gastro.selectedOrdersSubject.next(orders);
        },
        cancelSelection() {
            Gastro.selectedOrdersSubject.next([]);
            this.closePopup();
            nsSnackBar.success(__m('The selected orders were removed.', 'NsGastro')).subscribe();
        },
        async mergeSelectedOrders() {
            if (this.orders.length < 2) {
                return nsSnackBar.error(__m('You need to select at least 2 orders to merge them', 'NsGastro')).subscribe();
            }
            try {
                await new Promise((resolve, reject) => {
                    Popup.show(gastroPosMerge, {
                        resolve,
                        reject,
                    });
                });
            }
            catch (error) {
                console.log(error);
                return nsSnackBar.error(__m('An error occured while merging orders.', 'NsGastro')).subscribe();
            }
        }
    }
};
//# sourceMappingURL=gastro-pos-selected-orders.js.map