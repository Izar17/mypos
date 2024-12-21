declare const __;
declare const nsHttpClient;
declare const nsSnackBar;
declare const __m;
declare const ns;

export default {
    name: "gastro-kitchen-select",
    template: `
    <div class="bg-lg w-95vw max-h md:w-3/5-screen lg:w-2/5-screen ns-box">
        <div id="header" class="p-2 border-b ns-box-header flex justify-between">
            <h3 class="font-semibold">{{ localization( 'Kitchens', 'NsGastro' ) }}</h3>
            <div>
                <ns-close-button @click="closePopup()"></ns-close-button>
            </div>
        </div>
        <div class="h-44 flex items-center justify-center py-10" v-if="! loaded">
            <ns-spinner></ns-spinner>
        </div>
        <div  v-if="loaded && kitchens.length === 0" class="flex-auto flex flex-col items-center justify-center h-72 py-10">
            <i class="las la-frown text-6xl"></i>
            <h3 class="text-sm">{{ localization( 'Looks like there is no kitchens.', 'NsGastro' ) }}</h3>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3" v-if="loaded && kitchens.length > 0">
            <div @click="selectKitchen( kitchen )" v-for="kitchen of kitchens" :key="kitchen.id" class="border ns-numpad-key flex items-center justify-center flex-col p-3 cursor-pointer h-48">
                <h3 class="font-semibold text-primary">{{ kitchen.name }}</h3>
                <p class="text-sm text-secondary px-4 text-center">{{ kitchen.description || this.localization( 'No description provided', 'NsGastro' ) }}</p>
            </div>
        </div>
    </div>
    `,
    props: [ 'popup' ],
    data() {
        return {
            kitchens: [],
            loaded: false,
        }
    },
    computed: {
        selectedKitchen() {
            const selected  =   this.kitchens.filter( kitchen => kitchen.selected );
            
            if ( selected.length > 0 ) {
                return selected[0];
            }

            return false;
        }
    },
    mounted() {
        this.loadKitchens();
    },
    methods: {
        localization: __m,
        closePopup() {
            this.popup.params.reject( false );
            this.popup.close();
        },
        selectKitchen( kitchen ) {
            const indexOf   =   this.kitchens.indexOf( kitchen );

            this.kitchens.forEach( ( kitchen, index ) => {
                if ( index !== indexOf ) {
                    kitchen.selected    =   false;
                } else {
                    kitchen.selected    =   true;
                }
            });

            console.log( this.selectedKitchen );
            this.popup.params.resolve( this.selectedKitchen );
            this.popup.close();
        },
        loadKitchens() {
            this.loaded     =   false;
            nsHttpClient.get( `/api/gastro/available-kitchens` )
                .subscribe( result => {
                    this.kitchens   =   result.map( kitchen => {
                        kitchen.selected            =   false;
                        kitchen.range_starts        =   ns.date.moment.startOf( 'day' );
                        kitchen.range_ends          =   ns.date.moment.endOf( 'day' );
                        kitchen.refresh_interval    =   '5000';
                        return kitchen;
                    });
                    this.loaded     =   true;
                }, ( error ) => {
                    nsSnackBar.error( error.message || this.localization( 'Unexpected error occured', 'NsGastro' ) )
                        .subscribe();
                })
        }
    }
}