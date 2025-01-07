import gastroTableVue from './gastro-table';

declare const __m;
declare const Popup;

export default {
    name: "gastro-table-button",
    template: `
    <div class="ns-button hover-info">
        <button @click="openTableManagement()" type="button" class="flex-shrink-0 h-12 flex items-center shadow rounded px-2 py-1 text-sm">
            <i class="text-xl las la-utensils"></i>
            <span>{{ localization( 'Tables', 'NsGastro' ) }}</span>
        </button>
    </div>
    `,
    methods: {
        localization: __m,
        async openTableManagement() {
            try {
                const result    =   await new Promise( ( resolve, reject ) => {
                    Popup.show( gastroTableVue, { resolve, reject, mode: 'explore' });
                });
            } catch( exception ) {
                // the popup might be closed here
            }
        }
    }
}