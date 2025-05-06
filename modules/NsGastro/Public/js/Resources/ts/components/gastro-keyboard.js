export default {
    name: 'gastro-keyboard',
    template: `
    <div class="shadow-lg ns-box w-95vw md:w-2/5-screen">
        <div class="border-b ns-box-header p-2 flex go-items-center go-justify-between">
            <h3>{{ localization( 'Define Quantity:', 'NsGastro' ) }} {{ modifier.name }}</h3>
            <ns-close-button @click="closePopup()"></ns-close-button>
        </div>
        <div class="ns-box-body">
            <div class="text-3xl flex justify-end p-2">{{ modifier.quantity }}</div>
        </div>
        <div>
            <component  v-bind:is="keyboardComponent()" :value="modifier.quantity" @next="saveQuantity( $event )" @changed="updateModifierQuantity( $event )"></component>
        </div>
    </div>
    `,
    data() {
        return {
            keyboardComponent: () => nsComponents.nsNumpad,
        };
    },
    props: ['popup'],
    methods: {
        localization: __m,
        closePopup() {
            this.popup.params.reject(false);
            this.popup.close();
        },
        updateModifierQuantity(quantity) {
            this.modifier.quantity = quantity;
            this.$forceUpdate();
        },
        saveQuantity(quantity) {
            if (parseFloat(quantity) > 0) {
                this.modifier.quantity = parseFloat(this.modifier.quantity);
                this.popup.close();
                this.popup.params.resolve(this.modifier);
            }
            else {
                nsSnackBar.error(this.localization('Invalid quantity provided.', 'NsGastro')).subscribe();
            }
        },
    },
    computed: {
        modifier() {
            return this.popup.params.modifier;
        }
    }
};
//# sourceMappingURL=gastro-keyboard.js.map