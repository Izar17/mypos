import gastroPosReadyMealsVue from './gastro-pos-ready-meals';

declare const __m;
declare const Popup;
declare const nsHttpClient;
declare const Echo;

export default {
    name: "gastro-pos-orders-button",
    data() {
        return {
            readyMeals: 0
        }
    },
    template: `
    <div class="ns-button hover-info">
        <button @click="openReadyOrder()" class="relative flex-shrink-0 h-12 flex items-center shadow rounded px-2 py-1 text-sm ">
            <i class="text-xl las la-check-circle"></i>
            <span class="ml-1">{{ localization( 'Ready Meals', 'NsGastro' ) }}</span>
            <span class="h-6 w-6 ml-1 justify-center rounded-full flex items-center bg-info-tertiary text-white fond-bold">{{ readyMeals }}</span>
        </button>
    </div>
    `,
    methods: {
        localization: __m,
        async openReadyOrder() {
            try {
                const result    =   await new Promise( ( resolve, reject ) => {
                    Popup.show( gastroPosReadyMealsVue, { resolve, reject });
                });
            } catch( exception ) {

            }
        },
        getReadyMealCount() {
            nsHttpClient.get( `/api/gastro/products/count-ready` )
                .subscribe( result => {
                    this.readyMeals     =   result.readyMeals;
                })
        }
    },
    mounted() {
        this.getReadyMealCount();

        if ( typeof Echo !== 'undefined' ) {
            Echo.channel( `default-channel` )
                .listen( 'Modules\\NsGastro\\Events\\KitchenAfterUpdatedOrderEvent', (e) => {
                    console.log( e );
                    this.getReadyMealCount();
                });
        } else {
            setInterval( () => {
                this.getReadyMealCount();
            }, 10000 );
        }
    }
}