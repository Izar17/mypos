import gastroPosOrderMoveVue from './gastro-pos-order-move';
import gastroSplitOrder from './gastro-split-order';
export default {
    name: 'gastro-pos-order-options',
    template: `
    <div class="shadow-full ns-box w-95vw h-1/2 md:w-2/4-screen lg:w-2/6-screen overflow-hidden flex flex-col">
        <div class="border-b ns-box-header p-2 flex flex-col md:flex-row justify-between items-center">
            <div class="flex-auto">
                <h3 class="font-semibold mb-1 md:mb-0">{{ localization( 'Order Options', 'NsGastro' ) }}</h3>
            </div>
            <div class="flex items-center justify-between w-full md:w-auto">
                <div class="px-1">
                    <ns-close-button @click="closePopup()"></ns-close-button>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2">
            <template v-for="(option, index) of options" :key="index">
                <div
                    @click="option.onClick( order )" 
                    v-if="option.visible( order )"
                    class="border ns-numpad-key flex cursor-pointer items-center justify-center go-h-52 flex-col">
                    <i :class="option.icon" class="las go-text-8xl mr-1"></i>
                    <span>{{ option.label }}</span>
                </div>
            </template>
        </div>
    </div>
    `,
    props: ['popup'],
    data() {
        return {
            options: [],
            order: null
        };
    },
    mounted() {
        this.popupCloser();
        this.order = this.popup.params.order;
        this.options = nsHooks.applyFilters('ns-gastro-order-options', [
            {
                label: this.localization('Move', 'NsGastro'),
                icon: 'la-expand-arrows-alt ',
                visible: (order) => true,
                onClick: (order) => this.moveOrder(order)
            }, {
                label: this.localization('Request', 'NsGastro'),
                icon: 'la-mitten',
                visible: (order) => true,
                onClick: (order) => this.requestOrder(order)
            }, {
                label: this.localization('Split', 'NsGastro'),
                icon: 'la-cut',
                visible: (order) => {
                    return ['hold', 'unpaid'].includes(order.payment_status);
                },
                onClick: (order) => this.splitOrder(order)
            }, {
                label: this.localization('Select For Merge', 'NsGastro'),
                icon: 'la-cart-plus',
                visible: (order) => ['hold', 'unpaid'].includes(order.payment_status),
                onClick: (order) => this.selectForMerge(order)
            }
        ]);
        this.popupCloser();
    },
    methods: {
        localization: __m,
        popupCloser,
        popupResolver,
        closePopup() {
            this.popupResolver(false);
        },
        splitOrder(order) {
            try {
                const result = new Promise((resolve, reject) => {
                    Popup.show(gastroSplitOrder, { resolve, reject, order });
                });
            }
            catch (exception) {
                // ...
            }
        },
        async requestOrder(order) {
            if (order.gastro_order_status !== 'ready') {
                return nsSnackBar.error(this.localization('Unable to request an order that is not ready.', 'NsGastro')).subscribe();
            }
            Popup.show(nsConfirmPopup, {
                title: this.localization('Confirm Request', 'NsGastro'),
                message: this.localization('The request will be submitted to the kitchen.', 'NsGastro'),
                onAction: (action) => {
                    if (action) {
                        nsHttpClient.get(`/api/gastro/orders/${order.id}/request`)
                            .subscribe(result => {
                            this.popupResolver(true);
                            nsSnackBar
                                .success(result.message, this.localization('Ok', 'NsGastro'), { duration: 3000 })
                                .subscribe();
                        }, (error) => {
                            nsSnackBar
                                .error(error.message || this.localization('An unexpected error has occured.', 'NsGastro'), this.localization('Ok', 'NsGastro'), { duration: 3000 })
                                .subscribe();
                        });
                    }
                }
            });
        },
        async selectForMerge(order) {
            try {
                await Gastro.selectOrderForMerging(order);
                this.popup.close();
            }
            catch (exception) {
                // nothing to do here... we'll keep the popup open.
            }
        },
        async moveOrder(order) {
            try {
                const result = await new Promise((resolve, reject) => {
                    Popup.show(gastroPosOrderMoveVue, { resolve, reject, $parent: this, order });
                });
                this.popupResolver(true);
            }
            catch (exception) {
                console.log(exception);
            }
        }
    }
};
//# sourceMappingURL=gastro-pos-order-options.js.map