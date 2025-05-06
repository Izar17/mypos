import gastroKitchenSettingsVue from './gastro-kitchen-settings';
import gastroPosOrderOptionsVue from './gastro-pos-order-options';
import gastroPosProductOptionsVue from './gastro-pos-product-options';
import gastroPosSelectedOrdersVue from './gastro-pos-selected-orders';
import gastroSeatsVue from './gastro-seats';
export default {
    template: `
    <div class="shadow-full ns-box w-95vw h-95vh md:w-4/5-screen lg:w-4/6-screen md:h-4/5-screen overflow-hidden flex flex-col">
        <div class="border-b ns-box-header p-2 flex flex-col md:flex-row justify-between items-center" :class="selectedTable !== null ? ( selectedTable.busy ? 'bg-success-tertiary go-text-white' : 'text-primary' ) : ''" >
            <div class="flex-auto">
                <span class="font-semibold mb-1 md:mb-0">
                    <span v-if="! additionalTitle">{{ localization( 'Table Management', 'NsGastro' ) }}</span>
                    <span v-if="additionalTitle">{{ additionalTitle }}</span>
                </span>
            </div>
            <div class="flex items-center justify-between w-full md:w-auto">
                <div class="flex">
                    <div class="px-1" v-if="[ 'orders', 'sessions-orders' ].includes( screen )">
                        <button @click="openSettingsOptions()" class="outline-none rounded-full px-3 py-1 border ns-inset-button info">
                            <i class="las la-tools"></i>
                            <span>{{ localization( 'Settings', 'NsGastro' ) }}</span>
                        </button>
                    </div>
                    <div class="px-1" v-if="[ 'orders', 'sessions-orders', 'sessions' ].includes( screen ) && ns_gastro_enable_table_sessions">
                        <button :class="[ 'sessions', 'sessions-orders' ].includes( screen ) ? 'info' : ''" @click="toggleTableSessionHistory()" class="outline-none rounded-full px-3 py-1 border ns-inset-button">
                            <i class="las la-history"></i>
                            <span>{{ localization( 'Session History', 'NsGastro' ) }}</span>
                        </button>
                    </div>
                    <div class="px-1 -mx-1 flex flex-col md:flex-row" v-if="screen === 'tables'">
                        <div class="px-1">
                            <button :class="filterMode === 'busy' ? 'active' : ''" @click="filterOnlyBusy()" class="outline-none rounded-full px-3 py-1 border border-gray1200 ns-inset-button success">{{ localization( 'Only Busy Tables', 'NsGastro' ) }}</button>
                        </div>
                        <div class="px-1">
                            <button :class="filterMode === 'free' ? 'active' : ''" @click="filterOnlyAvailable()" class="outline-none rounded-full px-3 py-1 border border1gray-200 ns-inset-button success">{{ localization( 'Only Available Tables', 'NsGastro' ) }}</button>
                        </div>
                    </div>
                    <div class="px-1 -mx-2 flex" v-if="ns_gastro_areas_enabled && screen === 'tables'">
                        <div class="px-2">
                            <button @click="returnToAreas()" class="outline-none rounded-full px-3 py-1 ns-inset-button error border">{{ localization( 'Return', 'NsGastro' ) }}</button>
                        </div>
                    </div>
                    <div class="px-1" v-if="[ 'orders', 'sessions-orders' ].includes( screen )">
                        <button @click="returnToTables()" class="outline-none rounded-full px-3 py-1 ns-inset-button error border">{{ localization( 'Return', 'NsGastro' ) }}</button>
                    </div>
                </div>
                <div class="px-1">
                    <ns-close-button @click="closePopup()"></ns-close-button>
                </div>
            </div>
        </div>
        <div class="overflow-hidden flex flex-col flex-auto" v-if="[ 'orders', 'sessions-orders' ].includes( screen )">
            <div class="flex-auto overflow-y-auto" v-if="orders.length > 0">
                <div class="flex flex-wrap">
                    <div class="border border-box-edge w-full p-2 md:w-1/2" :key="order.id" v-for="order of orders">
                        <div class="flex justify-between p-2">
                            <h3 class="font-semibold">{{ order.code }}</h3>
                            <div class="-mx-1 flex items-center">
                                <div class="px-1" v-if="! [ 'canceled', 'ready' ].includes( order.gastro_order_status )">
                                    <!-- <button class="rounded-full px-3 bg-red-500 text-white">{{ localization( 'Cancel', 'NsGastro' ) }}</button> -->
                                </div>
                                <div class="px-1">
                                    <button @click="toggleDetails( order )" class="rounded-full px-3 ns-inset-button info border" v-if="! showDetails[ order.code ]">
                                        <i class="las la-eye"></i>
                                        <span class="text-sm">{{ localization( 'Show Details', 'NsGastro' ) }}</span>
                                    </button>
                                    <button @click="toggleDetails( order )" class="rounded-full px-3 ns-inset-button info border" v-if="showDetails[ order.code ]">
                                        <i class="las la-low-vision"></i>
                                        <span class="text-sm">{{ localization( 'Hide Details', 'NsGastro' ) }}</span>
                                    </button>
                                </div>
                                <div class="px-1">
                                    <button :disabled="true" v-if="order.gastro_order_status === 'ready'" class="rounded-full ns-inset-button border px-2 success">
                                        {{ localization( 'Ready', 'NsGastro' ) }}
                                    </button>
                                    <button :disabled="true" v-if="order.gastro_order_status === 'ongoing'" class="rounded-full ns-inset-button border px-2 info">
                                        {{ localization( 'Ongoing', 'NsGastro' ) }}
                                    </button>
                                    <button :disabled="true" v-if="order.gastro_order_status === 'pending'" class="rounded-full ns-inset-button border px-2 info">
                                        {{ localization( 'Pending', 'NsGastro' ) }}
                                    </button>
                                    <button :disabled="true" v-if="order.gastro_order_status === 'canceled'" class="rounded-full ns-inset-button border px-2 error">
                                        {{ localization( 'Canceled', 'NsGastro' ) }}
                                    </button>
                                    <button :disabled="true" v-if="order.gastro_order_status === 'requested'" class="rounded-full ns-inset-button border px-2 warning">
                                        {{ localization( 'Requested', 'NsGastro' ) }}
                                    </button>
                                    <button :disabled="true" v-if="order.gastro_order_status === 'processed'" class="rounded-full ns-inset-button border px-2 info">
                                        {{ localization( 'Processed', 'NsGastro' ) }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-if="showDetails[ order.code ]">
                            <div class="flex my-1 px-2 text-sm text-primary justify-between">
                                <span>{{ localization( 'Total', 'NsGastro' ) }}</span>
                                <span>{{ nsCurrency( order.total ) }}</span>
                            </div>
                            <div class="flex my-1 px-2 text-sm text-primary justify-between">
                                <span>{{ localization( 'Name', 'NsGastro' ) }}</span>
                                <span>{{ order.title || this.localization( 'N/A', 'NsGastro' ) }}</span>
                            </div>
                            <div class="flex my-1 px-2 text-sm text-primary justify-between">
                                <span>{{ localization( 'Waiter', 'NsGastro' ) }}</span>
                                <span>{{ order.user.username || this.localization( 'N/A', 'NsGastro' ) }}</span>
                            </div>
                            <div class="flex my-1 px-2 text-sm text-primary justify-between">
                                <span>{{ localization( 'Customer', 'NsGastro' ) }}</span>
                                <span>{{ order.customer.name || this.localization( 'N/A', 'NsGastro' ) }}</span>
                            </div>
                        </div>
                        <div class="bg-gray-100">
                            <div>
                                <div v-for="product of order.products" @click="showProductOptions( product )" 
                                    :class="getMealBGClass( product )"
                                    class="p-2 hover:bg-blue-100 mb-1 cursor-pointer" 
                                    :key="product.id">
                                    <div :class="getMealProductTextColor( product )" class="flex text-sm justify-between">
                                        <h4 class="font-semibold">{{ product.name }} (x{{ product.quantity }})</h4>
                                        <span>{{ nsCurrency( product.total_price ) }}</span>
                                    </div>
                                    <ul :class="getMealModifierTextColor( product )" class="text-xs">
                                        <li v-for="modifier of product.modifiers" :key="modifier.id" class="py-1 border-b border-dashed border-blue-400 flex justify-between">
                                            <span>
                                                {{ modifier.name }} (x{{ modifier.quantity }})
                                            </span>
                                            <span>
                                                {{ nsCurrency( modifier.total_price ) }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="flex flex-wrap">
                                <button v-if="order.payment_status !== 'paid'" @click="addProduct( order )" class="w-1/2 lg:w-1/4 flex items-center justify-center py-1 bg-blue-400 text-white font-semibold">
                                    <i class="las la-plus text-2xl mr-1"></i>
                                    <span>{{ localization( 'Add', 'NsGastro' ) }}</span>
                                </button>
                                <button v-if="order.payment_status !== 'paid'" @click="payOrder( order )" class="w-1/2 lg:w-1/4 flex items-center justify-center py-1 bg-green-400 text-white font-semibold">
                                    <i class="las la-money-bill-wave text-2xl mr-1"></i>
                                    <span>{{ localization( 'Pay', 'NsGastro' ) }}</span>
                                </button>
                                <button @click="printOrder( order )" class="w-1/2 lg:w-1/4 flex items-center justify-center py-1 elevation-surface border font-semibold">
                                    <i class="las la-print text-2xl mr-1"></i>
                                    <span>{{ localization( 'Print', 'NsGastro' ) }}</span>
                                </button>
                                <button @click="openOrderOption( order )" class="w-1/2 lg:w-1/4 flex items-center justify-center py-1 elevation-surface border font-semibold">
                                    <i class="las la-tools text-2xl mr-1"></i>
                                    <span>{{ localization( 'Options', 'NsGastro' ) }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="orders.length === 0 && ! ordersLoaded" class="w-full flex items-center justify-center flex-auto">
                <ns-spinner></ns-spinner>
            </div>
            <div v-if="orders.length === 0 && ordersLoaded" class="w-full flex-col text-primary flex items-center justify-center flex-auto">
                <i class="text-6xl las la-laugh-wink"></i>
                <h3 v-if="! ns_gastro_enable_table_sessions" class="font-semibold text-lg">{{ localization( 'No orders has been found', 'NsGastro' ) }}</h3>
                <div class="flex flex-col items-center" v-if="ns_gastro_enable_table_sessions">
                    <h3 class="font-semibold text-lg">{{ localization( 'No active sessions were found.', 'NsGastro' ) }}</h3>
                    <span class="text-sm">{{ localization( 'Please use the Session History to browse previous sessions', 'NsGastro' ) }}</span>
                </div>
            </div>
            <div class="p-2 flex md:items-center flex-col md:flex-row md:justify-between border-t ns-box-footer">
                <div class="flex -mx-2">
                    <div class="px-2">
                        <button @click="showTableHistory( selectedTable )" class="rounded-lg shadow hover:shadow-none border px-3 py-1 ns-inset-button">
                            <i class="las la-sync text-xl"></i>
                            <span class="go-hidden md:go-inline-block">{{ localization( 'Refresh', 'NsGastro' ) }}</span>
                        </button>
                    </div>
                    <div class="px-2" v-if="screen === 'orders'">
                        <button @click="setRange( 'today' ); showTableHistory( selectedTable )" class="outline-none rounded-lg px-3 py-1 ns-inset-button border">
                            <i class="las la-clock text-xl"></i>
                            <span>{{ localization( 'Today', 'NsGastro' ) }}</span>
                        </button>
                    </div>
                    <div class="px-2" v-if="screen === 'orders'">
                        <button @click="setRange( 'yesterday' ); showTableHistory( selectedTable )" class="outline-none rounded-lg px-3 py-1 ns-inset-button border">
                            <i class="las la-clock text-xl"></i>
                            <span>{{ localization( 'From Yesterday', 'NsGastro' ) }}</span>
                        </button>
                    </div>
                </div>
                <div class="w-full md:w-auto mt-2 md:mt-0 md:m-0 flex -mx-2">
                    <div class="px-2" v-if="selectedOrders.length > 0">
                        <span class="flex-auto justify-center flex items-center rounded cursor-pointer py-2 px-3 font-semibold shadow bg-info-secondary text-white" @click="handleSelectedOrders( selectedTable )" type="info">{{ localization( '{orderCount} Orders Selected', 'NsGastro' ).replace( '{orderCount}', selectedOrders.length ) }}</span>
                    </div>
                    <div class="px-2" v-if="selectedTable.busy">
                        <button class="flex-auto justify-center flex items-center rounded cursor-pointer py-2 px-3 font-semibold shadow bg-green-400 text-white" @click="setAvailable( selectedTable )" type="info">
                            <span class="go-hidden sm:go-inline"><i class="las la-door-open"></i> {{ localization( 'Set Free', 'NsGastro' ) }}</span>
                            <span class="go-inline sm:go-hidden"><i class="las la-door-open"></i></span>
                        </button>
                    </div>
                    <div class="px-2">
                        <button class="flex-auto justify-center flex items-center rounded cursor-pointer py-2 px-3 font-semibold shadow bg-blue-400 text-white" @click="proceedSelect( selectedTable )" type="info">
                            <span class="go-hidden sm:go-inline"><i class="las la-hand-pointer"></i> {{ localization( 'Select', 'NsGastro' ) }}</span>
                            <span class="go-inline sm:go-hidden"><i class="las la-hand-pointer"></i></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-y-auto flex flex-col p-4 flex-auto bg-gray-100" v-if="[ 'sessions' ].includes( screen )">
            <div class="flex -m-2 flex-wrap">
                <div v-for="session of sessions" :key="session.id" class="w-full mb-2 md:w-1/2 p-2">
                    <div class="rounded flex elevation-surface shadow overflow-hidden">
                        <div class="p-2 flex items-center justify-center">
                            <div class="go-w-14 go-h-14 rounded-full bg-blue-400 font-bold text-3xl text-white flex items-center justify-center"><i class="las la-stopwatch"></i></div>
                        </div>
                        <div class="p-2 flex-auto justify-center flex flex-col">
                            <h3 class="font-semibold">{{ session.name || this.localization( 'Unnamed Session', 'NsGastro' ) }}</h3>
                            <p class="text-sm">{{ localization( 'Active : ', 'NsGastro' ) }} {{ session.active ? this.localization( 'Yes', 'NsGastro' ) : this.localization( 'No', 'NsGastro' ) }}</p>
                            <p class="text-sm">{{ localization( 'Orders : ', 'NsGastro' ) }} {{ session.ordersCount }}</p>
                        </div>
                        <div v-if="session.active === 1" @click="closeSession( session )" class="border-l border-box-edge go-w-14 flex hover:bg-error-primary hover:text-white cursor-pointer items-center justify-center"><i class="las la-lock"></i></div>
                        <div v-if="session.active === 0" @click="openSession( session )" class="border-l border-box-edge go-w-14 flex hover:bg-info-primary hover:text-white cursor-pointer items-center justify-center"><i class="las la-unlock"></i></div>
                        <div @click="loadSessionOrders( session )" class="border-l border-box-edge go-w-14 flex hover:bg-blue-400 hover:text-white cursor-pointer items-center justify-center"><i class="las la-eye"></i></div>
                    </div>
                </div>
            </div>
            <div v-if="sessions.length === 0" class="p-2 flex flex-auto overflow-y-auto flex-wrap">
                <div  class="w-full flex-col text-primary flex items-center justify-center flex-auto">
                    <i class="text-9xl las la-frown"></i>
                    <h3 class="font-semibold text-lg">{{ localization( 'No sessions have been found', 'NsGastro' ) }}</h3>
                </div>
            </div>
        </div>
        <div :class="tables.length === 0 ? 'flex-auto': ''" class="overflow-y-auto flex" v-if="screen === 'tables'">
            <div class="w-full grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 anim-duration-500 fade-in-entrance" v-if="tables.length > 0">
                <div 
                    @touchstart="debounceForAvailability( table, $event )"
                    @touchend="mouseDown = false"
                    @click="selectQuantity( table )"
                    :key="table.id" 
                    v-for="table of tables" 
                    :class="table.selected ? 'bg-info-secondary border-info-tertiary' : ( table.busy ? 'border-success-tertiary bg-success-secondary' : 'border-box-edge' )"
                    class="relative border cursor-pointer relative flex-col go-h-52">
                    <div v-if="table.preview" :style="{ 'background-image' : 'url(' + table.preview + ')' }" class="go-w-full go-h-full absolute go-z-0 go-bg-cover go-bg-center">
                    </div>
                    <div :style="{ 'background' : ( table.busy ? 'rgb(35 151 77 / 34%)' : '' ) }" class="absolute w-full h-full go-z-20 flex items-center justify-center flex-col">
                        <div v-if="! table.busy && ns_gastro_seats_enabled" class="rounded-full -mb-6 go-h-10 w-10 flex items-center justify-center font-bold fond-bold gastro-pill info">{{ table.seats }}</div>
                        <div v-if="table.busy" class="rounded-full text-center -mb-6 go-h-10 px-3 flex items-center justify-center font-bold gastro-pill success">{{ localization( 'Currently Busy', 'NsGastro' ) }}</div>
                        <img v-if="!table.preview" :src="icons.chair">
                        <h1 :class="table.preview ? 'bg-box-background' : ''" class="go-rounded-full go-text-xs md:go-text-sm go-p-3">{{ table.name }}</h1>
                    </div>
                </div>
            </div>
            <div v-if="tables.length === 0 && tableLoaded" class="w-full flex flex-col items-center justify-center flex-auto text-primary">
                <i class="go-text-9xl las la-frown"></i>
                <h3 class="font-semibold text-lg">{{ localization( 'No tables has been found', 'NsGastro' ) }}</h3>
                <p v-if="ns_gastro_tables_assignation_enabled" class="text-sm">{{ localization( 'You might check if tables are assigned to your account.', 'NsGastro' ) }}</p>
            </div>
            <div v-if="tables.length === 0 && ! tableLoaded" class="w-full flex items-center justify-center flex-auto">
                <ns-spinner></ns-spinner>
            </div>
        </div>
        <div :class="areas.length === 0 ? 'flex-auto' : ''" class="overflow-y-auto flex" v-if="screen === 'areas'">
            <div class="w-full grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 anim-duration-500 fade-in-entrance" v-if="areas.length > 0">
                <div @click="loadTables( area )" :key="area.id" v-for="area of areas" class="cursor-pointer border flex-col border-box-edge go-h-52 flex items-center justify-center">
                    <img :src="icons.menu">
                    <h1 class="my-3">{{ area.name }}</h1>
                </div>
            </div>
            <div v-if="areas.length === 0 && areasLoaded" class="w-full flex-col flex-auto flex items-center justify-center text-primary">
                <i class="text-9xl las la-frown"></i>
                <h3 class="font-semibold text-lg">{{ localization( 'No areas has been found', 'NsGastro' ) }}</h3>
            </div>
            <div v-if="areas.length === 0 && ! areasLoaded" class="w-full flex items-center justify-center flex-auto">
                <ns-spinner></ns-spinner>
            </div>
        </div>
    </div>
    `,
    props: ['popup'],
    mounted() {
        if (this.ns_gastro_areas_enabled) {
            this.loadAreas();
            this.screen = 'areas';
        }
        else {
            this.loadTables();
            this.screen = 'tables';
        }
        this.mode = this.popup.params.mode || 'select';
        console.log({ ns });
        if (Echo.connector.socket && Echo.connector.socket.connected) {
            this.listenSockets();
        }
        else {
            this.launchIntervalFetches();
        }
        Gastro.tableOpenedSubject.next(true);
        this.popupCloser();
        this.selectedOrdersSubscription = Gastro.selectedOrdersSubject.subscribe(orders => {
            this.selectedOrders = orders;
            this.$forceUpdate();
        });
        nsHooks.addAction('ns-pos-payment-destroyed', 'gastro-reset-cart', () => {
            if (this.isPaying) {
                POS.reset();
                this.isPaying = false;
            }
        });
    },
    beforeUnmount() {
        Gastro.tableOpenedSubject.next(false);
        clearTimeout(this.intervalFetches);
    },
    data() {
        const rangeStars = ns.date.getMoment().startOf('day').format();
        const rangeEnds = ns.date.getMoment().endOf('day').format();
        return {
            selectedOrders: [],
            selectedOrdersSubscription: null,
            screen: 'areas',
            tableLoaded: false,
            mouseDown: false,
            ordersLoaded: false,
            intervalFetches: null,
            additionalTitle: null,
            areasLoaded: false,
            selectedArea: null,
            selectedTable: null,
            selectedSessions: null,
            isPaying: false,
            areas: [],
            tables: [],
            showDetails: {},
            sessions: [],
            orders: [],
            filterMode: '',
            mode: 'select',
            settings: {
                range_starts: rangeStars,
                range_ends: rangeEnds,
            },
            ...GastroSettings
        };
    },
    computed: {
        order() {
            return POS.order.getValue();
        },
    },
    methods: {
        localization: __m,
        nsCurrency,
        popupCloser,
        popupResolver,
        toggleDetails(order) {
            this.showDetails[order.code] = !this.showDetails[order.code];
            this.$forceUpdate();
        },
        launchIntervalFetches() {
            this.intervalFetches = setInterval(() => {
                if (this.screen === 'orders') {
                    this.showTableHistory(this.selectedTable);
                }
                if (this.screen === 'sessions-orders') {
                    this.loadSessionOrders(this.selectedSessions);
                }
            }, 5000);
        },
        async handleSelectedOrders() {
            try {
                const popup = await new Promise((resolve, reject) => {
                    Popup.show(gastroPosSelectedOrdersVue, { resolve, reject });
                });
                console.log(popup);
            }
            catch (error) {
                console.error(error);
            }
        },
        toggleTableSessionHistory() {
            if (this.screen !== 'sessions') {
                this.sessions = [];
                nsHttpClient.post(`/api/gastro/tables/${this.selectedTable.id}/sessions`, this.settings)
                    .subscribe({
                    next: result => {
                        this.sessions = result;
                        this.screen = 'sessions';
                    },
                    error: (error) => {
                        nsSnackBar.error(this.localization('An unexpected error has occured.', 'NsGastro')).subscribe();
                    }
                });
            }
            else {
                this.screen = 'orders';
                this.showTableHistory(this.selectedTable);
            }
        },
        loadSessionOrders(session) {
            this.screen = 'sessions-orders';
            this.selectedSessions = session;
            nsHttpClient.post(`/api/gastro/tables/${this.selectedTable.id}/sessions/${session.id}/orders`, this.settings)
                .subscribe({
                next: result => {
                    this.orders = result;
                },
                error: (error) => {
                    nsSnackBar.error(this.localization('An unexpected error has occured.', 'NsGastro')).subscribe();
                }
            });
        },
        openSession(session) {
            Popup.show(nsConfirmPopup, {
                title: this.localization('Confirm Your Action', 'NsGastro'),
                message: this.localization('Would you like to open this session ?', 'NsGastro'),
                onAction: (action) => {
                    if (action) {
                        nsHttpClient.put(`/api/gastro/tables/${this.selectedTable.id}/sessions/${session.id}/action`, { action: 'open' })
                            .subscribe({
                            next: result => {
                                this.toggleTableSessionHistory();
                                nsSnackBar.success(result.message, this.localization('Okay', 'NsGastro'), { duration: 3000 }).subscribe();
                            },
                            error: (error) => {
                                nsSnackBar.error(this.localization('An unexpected error has occured.', 'NsGastro')).subscribe();
                            }
                        });
                    }
                }
            });
        },
        closeSession(session) {
            Popup.show(nsConfirmPopup, {
                title: this.localization('Confirm Your Action', 'NsGastro'),
                message: this.localization('Would you like to close this session manually ?', 'NsGastro'),
                onAction: (action) => {
                    if (action) {
                        nsHttpClient.put(`/api/gastro/tables/${this.selectedTable.id}/sessions/${session.id}/action`, { action: 'close' })
                            .subscribe({
                            next: result => {
                                this.toggleTableSessionHistory();
                                nsSnackBar.success(result.message, this.localization('Okay', 'NsGastro'), { duration: 3000 }).subscribe();
                            },
                            error: (error) => {
                                nsSnackBar.error(this.localization('An unexpected error has occured.', 'NsGastro')).subscribe();
                            }
                        });
                    }
                }
            });
        },
        listenSockets() {
            Echo.channel(`default-channel`)
                .listen('App\\Events\\OrderAfterCreatedEvent', (e) => {
                this.showTableHistory(this.selectedTable);
            })
                .listen('Modules\\NsGastro\\Events\\TableAfterUpdatedEvent', (e) => {
                this.showTableHistory(this.selectedTable);
            })
                .listen('App\\Events\\OrderAfterUpdatedEvent', (e) => {
                this.showTableHistory(this.selectedTable);
            });
        },
        filterOnlyBusy() {
            if (['', 'free'].includes(this.filterMode)) {
                this.filterMode = 'busy';
            }
            else {
                this.filterMode = '';
            }
            this.loadTables(this.selectedArea);
        },
        filterOnlyAvailable() {
            if (['', 'busy'].includes(this.filterMode)) {
                this.filterMode = 'free';
            }
            else {
                this.filterMode = '';
            }
            this.loadTables(this.selectedArea);
        },
        setRange(range) {
            switch (range) {
                case 'today':
                    this.settings.range_starts = moment(ns.date.current).startOf('day').format('YYYY/MM/DD HH:mm:ss');
                    this.settings.range_ends = moment(ns.date.current).endOf('day').format('YYYY/MM/DD HH:mm:ss');
                    break;
                case 'yesterday':
                    this.settings.range_starts = moment(ns.date.current).subtract(1, 'days').startOf('day').format('YYYY/MM/DD HH:mm:ss');
                    this.settings.range_ends = moment(ns.date.current).endOf('day').format('YYYY/MM/DD HH:mm:ss');
                    break;
                case 'week':
                    this.settings.range_starts = moment(ns.date.current).subtract(6, 'days').startOf('day').format('YYYY/MM/DD HH:mm:ss');
                    this.settings.range_ends = moment(ns.date.current).endOf('day').format('YYYY/MM/DD HH:mm:ss');
                    break;
            }
        },
        debounceForAvailability(table, e) {
            if (table.busy) {
                this.mouseDown = true;
                setTimeout(() => {
                    if (this.mouseDown) {
                        this.setAvailable(table);
                        e.preventDefault();
                    }
                }, 600);
            }
        },
        async openOrderOption(order) {
            try {
                const result = await new Promise((resolve, reject) => {
                    Popup.show(gastroPosOrderOptionsVue, { resolve, reject, order });
                });
                this.showTableHistory(this.selectedTable);
            }
            catch (exception) {
                console.log(exception);
            }
        },
        addProduct(order) {
            Gastro.selectedOrder.next(order);
            Gastro.setAddButtonsVisibility('visible');
            this.popup.close();
        },
        printOrder(order) {
            POS.print.process(order.id, 'sale', 'aloud');
        },
        async payOrder(order) {
            const popup = Popup.show(nsPOSLoadingPopup);
            const oldOrder = POS.order.getValue();
            try {
                await POS.loadOrder(order.id);
                const newOrder = POS.order.getValue();
                if (newOrder.payment_status === 'paid') {
                    POS.reset();
                    return nsSnackBar.error(this.localization('Unable to make a payment for an already paid order.', 'NsGastro'))
                        .subscribe();
                }
                this.proceedCustomerLoading(oldOrder);
                /**
                 * the script shold be aware the payment
                 * popup was opened from the PayButton
                 */
                this.isPaying = true;
            }
            catch (exception) {
                console.log(exception);
            }
            popup.close();
        },
        async proceedCustomerLoading(oldOrder) {
            const queues = [
                ProductsQueue,
                CustomerQueue,
                TypeQueue,
                PaymentQueue
            ];
            const order = POS.order.getValue();
            for (let index in queues) {
                try {
                    const promise = new queues[index](order);
                    const response = await promise.run();
                }
                catch (exception) {
                    /**
                     * in case there is something broken
                     * on the promise, we just stop the queue.
                     */
                    return false;
                }
            }
        },
        async openSettingsOptions() {
            try {
                const result = await new Promise((resolve, reject) => {
                    Popup.show(gastroKitchenSettingsVue, { fields: [
                            {
                                type: 'datetimepicker',
                                name: 'range_starts',
                                label: this.localization('Start Range', 'NsGastro'),
                                value: this.settings.range_starts,
                                description: this.localization('Define when from which moment the orders should be fetched.', 'NsGastro'),
                            }, {
                                type: 'datetimepicker',
                                name: 'range_ends',
                                label: this.localization('End Range', 'NsGastro'),
                                value: this.settings.range_ends,
                                description: this.localization('Define till which moment the orders should be fetched.', 'NsGastro'),
                            }
                        ], resolve, reject, settings: this.settings, title: this.localization('Settings', 'NsGastro') });
                });
                this.settings = result;
                this.showTableHistory(this.selectedTable);
            }
            catch (exception) {
                console.log(exception);
            }
        },
        getMealBGClass(product) {
            switch (product.cooking_status) {
                case 'ready':
                    return 'bg-success-secondary';
                    break;
                case 'ongoing':
                    return 'bg-info-secondary';
                    break;
                case 'canceled':
                    return 'bg-input-disabled';
                    break;
                case 'processed':
                    return 'bg-success-secondary';
                    break;
                case 'requested':
                    return 'bg-warning-secondary';
                    break;
            }
        },
        getMealProductTextColor(product) {
            switch (product.cooking_status) {
                case 'canceled':
                    return 'text-secondary';
                    break;
                default:
                    return 'text-primary';
            }
        },
        getMealModifierTextColor(product) {
            switch (product.cooking_status) {
                case 'canceled':
                    return 'text-secondary';
                    break;
                default:
                    return 'text-primary';
            }
        },
        closePopup() {
            this.popup.close();
            this.popup.params.reject(false);
        },
        returnToAreas() {
            this.loadAreas();
        },
        returnToTables() {
            this.selectedTable = null;
            this.loadTables(this.selectedArea);
        },
        loadAreas() {
            this.screen = 'areas';
            this.areasLoaded = false;
            nsHttpClient.get(`/api/gastro/areas`)
                .subscribe({
                next: result => {
                    this.areasLoaded = true;
                    this.areas = result;
                },
                error: (error) => {
                    nsSnackBar.error(error.message || this.localization('An unexpected error has occured.', 'NsGastro'), this.localization('OK', 'NsGastro'), { duration: 0 }).subscribe();
                }
            });
        },
        /**
         * Will set a busy table as available
         * @param {table} table
         * @return void
         */
        setAvailable(table) {
            Popup.show(nsConfirmPopup, {
                title: this.localization(`Set the table as available ?`, 'NsGastro'),
                message: this.localization(`You'll set the table as available, please confirm your action.`, 'NsGastro'),
                onAction: (action) => {
                    if (action) {
                        nsHttpClient.post(`/api/gastro/tables/${table.id}/change-availability`, {
                            status: 'available'
                        }).subscribe({
                            next: result => {
                                // this should refresh the tables
                                this.loadTables(this.selectedArea);
                                nsSnackBar
                                    .success(result.message, this.localization('OK', 'NsGastro'), { duration: 3000 })
                                    .subscribe();
                            },
                            error: (error) => {
                                nsSnackBar
                                    .error(error.message || this.localization('An unexpected error has occured.', 'NsGastro'), this.localization('OK', 'NsGastro'), { duration: 3000 })
                                    .subscribe();
                            }
                        });
                    }
                }
            });
        },
        loadTables(area = null) {
            this.selectedArea = area;
            this.screen = 'tables';
            this.tableLoaded = false;
            this.additionalTitle = null;
            const subscription = area === null ?
                nsHttpClient.get(`/api/gastro/tables?filter=${this.filterMode}`) :
                nsHttpClient.get(`/api/gastro/areas/${area.id}/available-tables?filter=${this.filterMode}`);
            subscription.subscribe({
                next: result => {
                    this.tableLoaded = true;
                    this.tables = result.map(table => {
                        table.selectedSeats = 1;
                        table.selected = this.order.table && this.order.table.id === table.id ? this.order.table : false;
                        return table;
                    });
                },
                error: (error) => {
                    nsSnackBar.error(error.message || this.localization('An unexpected error has occured.', 'NsGastro'), this.localization('OK', 'NsGastro'), { duration: 0 }).subscribe();
                }
            });
        },
        selectQuantity(table) {
            if (this.mode === 'select') {
                return this.proceedSelect(table);
            }
            else {
                return this.showTableHistory(table);
            }
        },
        showTableHistory(table) {
            this.selectedTable = table;
            this.additionalTitle = this.localization('{table} : Orders History - {availability}', 'NsGastro')
                .replace('{availability}', table.busy ? this.localization('Busy', 'NsGastro') : this.localization('Available', 'NsGastro'))
                .replace('{table}', table.name);
            this.screen = 'orders';
            this.ordersLoaded = false;
            nsHttpClient.post(`/api/gastro/tables/${table.id}/orders`, this.settings)
                .subscribe({
                next: orders => {
                    this.ordersLoaded = true;
                    orders.map(order => {
                        if (this.showDetails[order.code] === undefined) {
                            this.showDetails[order.code] = false;
                        }
                        return order;
                    });
                    this.orders = orders;
                }
            });
        },
        async showProductOptions(product) {
            try {
                const result = await new Promise((resolve, reject) => {
                    Popup.show(gastroPosProductOptionsVue, { resolve, reject, product });
                });
                /**
                 * Will refresh the table, to ensure changes
                 * are reflected.
                 */
                this.showTableHistory(this.selectedTable);
            }
            catch (exception) {
                console.log(exception);
            }
        },
        async proceedSelect(table) {
            if (this.ns_gastro_seats_enabled) {
                try {
                    /**
                     * restoring the default selected
                     * seats for the unselected tables.
                     */
                    this.tables.forEach(table => {
                        table.selected = false;
                        table.selectedSeats = 1;
                    });
                    table = await new Promise((resolve, reject) => {
                        Popup.show(gastroSeatsVue, { resolve, reject, table });
                    });
                }
                catch (exception) {
                    return nsSnackBar.error(this.localization('You need to define the seats before proceeding.', 'NsGastro')).subscribe();
                }
            }
            /**
             * the table quantity should have been upated now.
             * let's make sure that table is selected.
             */
            table.selected = true;
            /**
             * we'll update the table and the area used
             * for the ongoing order.
            */
            this.order.table_id = table.id;
            this.order.table = table;
            this.order.area_id = table.area_id;
            /**
             * if a table is selected without defining the
             * order type, we'll select "Dine in" automatically
             */
            if (this.order.type === undefined) {
                this.order.type = Gastro.getType();
            }
            /**
             * update order type label, so that
             * it has the table name on the type label
             */
            this.order.type.label = Gastro.getType().label;
            POS.order.next(this.order);
            this.popup.close();
            this.popup.params.resolve(table);
        }
    }
};
//# sourceMappingURL=gastro-table.js.map