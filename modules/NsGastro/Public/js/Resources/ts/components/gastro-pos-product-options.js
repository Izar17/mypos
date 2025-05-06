export default {
    name: "gastro-pos-product-options",
    template: `
    <div class="ns-box shadow-lg w-95vw h-1/2 md:w-2/4-screen lg:w-2/6-screen overflow-hidden">
        <div class="p-2 border-b ns-box-header flex justify-between items-center">
            <span>{{ localization( 'Product Options', 'NsGastro' ) }}</span>
            <div>
                <ns-close-button @click="popupResolver( false )"></ns-close-button>
            </div>
        </div>
        <div class="grid grid-cols-2">
            <div @click="serveMeal()" :class="product.cooking_status === 'ready' ? 'cursor-pointer' : 'cursor-not-allowed'" class="h-32 border ns-numpad-key flex items-center justify-center flex-col">
                <i class="text-6xl las la-concierge-bell"></i>
                <span>{{ localization( 'Served', 'NsGastro' ) }}</span>
            </div>
            <div @click="cancelMeal()" :class="product.cooking_status !== 'canceled' ? 'cursor-pointer' : 'cursor-not-allowed'"  class="cursor-pointer h-32 border ns-numpad-key flex items-center justify-center flex-col">
                <i class="text-6xl las la-times"></i>
                <span>{{ localization( 'Cancel', 'NsGastro') }}</span>
            </div>
            <div @click="updateNote()" :class="product.cooking_status === 'pending' ? 'cursor-pointer' : 'cursor-not-allowed'" class="h-32 border ns-numpad-key flex items-center justify-center flex-col">
                <i class="text-6xl las la-comment-alt"></i>
                <span>{{ localization( 'Note', 'NsGastro') }}</span>
            </div>
        </div>
    </div>
    `,
    computed: {
        product() {
            return this.popup.params.product;
        }
    },
    props: ['popup'],
    mounted() {
        this.popupCloser();
    },
    methods: {
        localization: __m,
        popupResolver,
        popupCloser,
        async updateNote() {
            if (this.product.cooking_status !== 'pending') {
                return nsSnackBar.error(this.localization('Unable to edit this product notes.', 'NsGastro')).subscribe();
            }
            try {
                const note = await new Promise((resolve, reject) => {
                    Popup.show(nsPromptPopup, {
                        resolve,
                        reject,
                        input: this.product.cooking_note,
                        title: 'Meal Note',
                        message: 'The following note will be visible at the kitchen and on the kitchen slip.',
                        onAction: (output) => {
                            resolve(output);
                        }
                    });
                });
                this.product.cooking_note = note;
                nsHttpClient.post(`/api/gastro/products/${this.product.id}/note`, { note })
                    .subscribe(result => {
                    this.popupResolver(this.product);
                    nsSnackBar.success(result.message).subscribe();
                }, (error) => {
                    nsSnackBar.error(error.message || this.localization('An unexpected error occured.', 'NsGastro')).subscribe();
                });
            }
            catch (exception) {
                console.log(exception);
            }
        },
        async serveMeal() {
            if (this.product.cooking_status !== 'ready') {
                return nsSnackBar.error(this.localization('Unable to serve a meal that is not ready.', 'NsGastro')).subscribe();
            }
            try {
                const result = await new Promise((resolve, reject) => {
                    Popup.show(nsConfirmPopup, {
                        title: this.localization('Would You Serve The Meal ?', 'NsGastro'),
                        resolve,
                        reject,
                        message: this.localization(`You're about to serve the meal "{product}". note that this operation can\'t be canceled.`, 'NsGastro').replace('{product}', this.product.name),
                        onAction: (action) => {
                            if (action) {
                                nsHttpClient.post(`/api/gastro/products/${this.product.id}/serve`, {
                                    reason: action
                                })
                                    .subscribe(result => {
                                    nsSnackBar.success(result.message).subscribe();
                                    this.popupResolver(result);
                                }, (error) => {
                                    nsSnackBar.error(error.message || this.localization('An unexpected error occured.', 'NsGastro')).subscribe();
                                });
                            }
                        }
                    });
                });
            }
            catch (exception) {
                console.log(exception);
            }
        },
        printCanceledMeal(order_id, products = []) {
            Gastro.printOrderCanceledMealKitchen(order_id, products);
        },
        async cancelMeal() {
            if (this.product.cooking_status === 'canceled') {
                return nsSnackBar.error(this.localization('Unable to cancel an already canceled product.', 'NsGastro')).subscribe();
            }
            try {
                const result = await new Promise((resolve, reject) => {
                    Popup.show(nsPromptPopup, {
                        title: this.localization('Confirm Your Action', 'NsGastro'),
                        resolve,
                        reject,
                        message: this.localization(`You're about to cancel "{product}". Please provide a reason for this action.`, 'NsGastro').replace('{product}', this.product.name),
                        onAction: (action) => {
                            if (typeof action === 'string') {
                                nsHttpClient.post(`/api/gastro/products/${this.product.id}/cancel`, {
                                    reason: action
                                })
                                    .subscribe(result => {
                                    console.log(this.product);
                                    nsSnackBar.success(result.message).subscribe();
                                    this.product = result.data.product;
                                    this.printCanceledMeal(this.product.order_id, [this.product]);
                                }, (error) => {
                                    nsSnackBar.error(error.message || this.localization('An unexpected error occured.', 'NsGastro')).subscribe();
                                });
                            }
                        }
                    });
                });
            }
            catch (exception) {
                console.log(exception);
            }
        }
    }
};
//# sourceMappingURL=gastro-pos-product-options.js.map