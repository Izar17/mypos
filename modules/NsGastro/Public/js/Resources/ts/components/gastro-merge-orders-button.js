import gastroPosMergeVue from './gastro-pos-merge';
export default {
    template: `
    <div class="ns-button hover-warning">
        <button @click="openMergeOrderPopup()" class="flex-shrink-0 h-12 flex items-center shadow rounded px-2 py-1 text-sm">
            <i class="text-xl las la-compress-arrows-alt mr-2"></i>
            <span class="ml-1">{{ localization( 'Merge Orders', 'NsGastro' ) }}</span>
            <span v-if="orderSelected > 0" class="h-6 w-6 ml-1 justify-center rounded-full flex items-center bg-info-tertiary text-white fond-bold">{{ orderSelected }}</span>
        </button>
    </div>
    `,
    mounted() {
        this.orderSelectedSubscription = Gastro.selectedOrdersSubject.subscribe((orders) => {
            this.orderSelected = orders.length;
        });
    },
    beforeUnmount() {
        this.orderSelectedSubscription.unsubscribe();
    },
    data() {
        return {
            orderSelected: 0,
            orderSelectedSubscription: null
        };
    },
    methods: {
        localization: __m,
        async openMergeOrderPopup() {
            try {
                const result = await new Promise((resolve, reject) => {
                    Popup.show(gastroPosMergeVue, { resolve, reject });
                });
            }
            catch (exception) {
                console.log(exception);
            }
        }
    }
};
//# sourceMappingURL=gastro-merge-orders-button.js.map