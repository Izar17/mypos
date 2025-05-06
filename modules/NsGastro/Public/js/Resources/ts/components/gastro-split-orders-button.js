import gastroSplitOrderVue from './gastro-split-order';
export default {
    template: `
    <div class="ns-button">
        <div class="ns-button hover-warning">
            <button @click="openSplitOrderPopup()" class="flex-shrink-0 h-12 flex items-center shadow rounded px-2 py-1 text-sm">
                <i class="text-xl las la-expand-arrows-alt mr-2"></i>
                <span>{{ localization( 'Split Orders', 'NsGastro' ) }}</span>
            </button>
        </div>
    </div>
    `,
    methods: {
        localization: __m,
        async openSplitOrderPopup() {
            try {
                const result = await new Promise((resolve, reject) => {
                    Popup.show(gastroSplitOrderVue, { resolve, reject });
                });
            }
            catch (exception) {
                console.log(exception);
            }
        }
    }
};
//# sourceMappingURL=gastro-split-orders-button.js.map