export default {
    name: 'gastro-pos-meal',
    template: `
    <div class="shadow-lg w-95vw md:w-3/5-screen lg:w-2/5-screen ns-box">
        <div class="p-2 flex justify-between border-b ns-box-header items-center">
            <h3 class="w-full">
                <span>{{ localization( 'Meal Status: ', 'NsGastro' ) }}</span>
                <span v-if="product">{{ product.name }}</span>
            </h3>
            <div>
                <ns-close-button @click="closePopup()"></ns-close-button>
            </div>
        </div>
        <div>
            <div class="go-grid go-grid-cols-2 text-primary">
                <div @click="printKitchen()" class="cursor-pointer hover:bg-info-secondary hover:go-text-white go-border border-box-edge go-h-36 flex items-center flex-col justify-center">
                    <i class="go-text-6xl las la-print"></i>
                    <span class="font-bold">{{ localization( 'Print', 'NsGastro' ) }}</span>
                </div>
                <div @click="cancelMeal()" class="cursor-pointer hover:bg-info-secondary hover:go-text-white go-border border-box-edge go-h-36 flex items-center flex-col justify-center">
                    <i class="go-text-6xl las la-ban"></i>
                    <span class="font-bold">{{ localization( 'Cancel', 'NsGastro' ) }}</span>
                </div>
                <div @click="addProductNote()" class="cursor-pointer hover:bg-info-secondary hover:go-text-white go-border border-box-edge go-h-36 flex items-center flex-col justify-center">
                    <i class="go-text-6xl las la-comment-alt"></i>
                    <span class="font-bold">{{ localization( 'Note', 'NsGastro' ) }}</span>
                </div>
            </div>
        </div>
    </div>
    `,
    props: ['popup'],
    mounted() {
        this.product = this.popup.params.product;
    },
    data() {
        return {
            product: null
        };
    },
    methods: {
        localization: __m,
        closePopup() {
            this.popup.params.reject(false);
            this.popup.close();
        },
        printKitchen() {
            const product = this.popup.params.product;
            if (['pending', 'ongoing'].includes(product.cooking_status) && product.id !== undefined) {
            }
            nsSnackBar.error('Unable to print a meal that is not yet send at the kitchen or which is already cooked.').subscribe();
        },
        cancelMeal() {
            const product = this.popup.params.product;
            if (['pending', 'ongoing'].includes(product.cooking_status) && product.id !== undefined) {
            }
            nsSnackBar.error('Unable to cancel a meal that is not send to the kitchen or which is already cookied.').subscribe();
        },
        async addProductNote() {
            try {
                const result = await new Promise((resolve, reject) => {
                    Popup.show(nsPromptPopup, {
                        resolve,
                        reject,
                        input: this.popup.params.product.cooking_note,
                        title: 'Meal Note',
                        message: 'The following note will be visible at the kitchen and on the kitchen slip.',
                        onAction: (output) => {
                            resolve(output);
                        }
                    });
                });
                if (result !== false) {
                    this.popup.params.product.cooking_note = result;
                }
                this.closePopup();
            }
            catch (exception) {
                console.log(exception);
            }
        }
    }
};
//# sourceMappingURL=gastro-pos-meal.js.map