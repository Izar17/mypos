export default {
    name: 'gastro-pos-merge',
    template: `
    <div class="ns-box go-relative shadowl-lg w-95vw h-95vh lg:w-2/3-screen flex flex-col text-gray-700 overflow-hidden">
        <div class="border-b ns-box-header p-2 flex justify-between items-center">
            <span class="font-semibold">{{ localization( 'Merge Orders', 'NsGastro' ) }}</span>
            <div>
                <ns-close-button @click="popupResolver(false)"></ns-close-button>
            </div>
        </div>
        <div class="p-2 ns-box-body flex flex-col flex-auto overflow-hidden">
            <div class="rounded overflow-hidden border-2 flex flex-shrink-0 input-group info w-full">
                <input ref="searchField" :placeholder="this.localization( 'Order Code', 'NsGastro' )" v-model="search" type="text" class="flex-auto p-2 outline-none">
                <button @click="searchOrderWithQuery( search )" class="px-3 py-2">{{ localization( 'Search', 'NsGastro' ) }}</button>
            </div>
            <div class="h-0 relative">
                <div class="shadow elevation-surface w-full absolute z-10">
                    <ul>
                        <li 
                            v-for="order of searchResults" 
                            :key="order.id" 
                            @click="addToTheQueue( order )"
                            class="cursor-pointer p-2 border-b border-box-edge flex flex-col justify-between">
                                <div class="flex justify-between">
                                    <h2 class="font-semibold text-primary">{{ order.code }}</h2>
                                    <span class="text-primary">{{ nsCurrency( order.total ) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <div>
                                        <span class="text-sm text-secondary">{{ localization( 'Customer: ', 'NsGastro' ) }} {{ order.customer.name }}</span>
                                    </div>
                                    <div>
                                        <span class="text-sm text-secondary">{{ localization( 'Order Type: ', 'NsGastro' ) }} {{ getOrderType( order.type ) }}</span>
                                    </div>
                                </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="py-2 md:py-4">
                <div class="-mx-2 md:-mx-4 flex flex-wrap">
                    <div class="px-2 md:px-4 w-1/2">
                        <div class="shadow elevation-surface border rounded p-2">
                            <h4 class="font-semibold">{{ localization( 'Total', 'NsGastro' ) }}</h4>
                            <h2 class="font-bold text-2xl md:text-4xl">{{ nsCurrency( totalOrders ) }}</h2>
                        </div>
                    </div>
                    <div class="px-2 md:px-4 w-1/2">
                        <div class="shadow elevation-surface border rounded p-2">
                            <h4 class="font-semibold">{{ localization( 'Products', 'NsGastro' ) }}</h4>
                            <h2 class="font-bold text-2xl md:text-4xl">{{ totalProducts }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-y-auto flex-auto">
                <div class="p-2">
                    <div class="flex -mx-2 md:flex-nowrap flex-wrap">
                        <div class="w-full md:w-1/2 lg:w-2/6 px-2">
                            <h3 class="border-b font-semibold text-sm border-info-tertiary py-2">{{ localization( 'Queued Orders', 'NsGastro' ) }}</h3>
                            <ul class="py-2">
                                <li v-for="order of queuedOrders" :key="order.id" class="shadow elevation-surface border rounded p-2 flex items-center justify-between">
                                    <div class="flex flex-auto justify-between">
                                        <span>{{ order.code }}</span>
                                        <span>{{ nsCurrency( order.total ) }}</span>
                                    </div>
                                    <div class="ml-2">
                                        <ns-close-button @click="removeOrderFromQueue( order )"></ns-close-button>
                                    </div>
                                </li>
                                <li v-if="queuedOrders.length === 0" class="p-2 shadow elevation-surface border text-center">{{ localization( 'No order has been queued.', 'NsGastro' ) }}</li>
                            </ul>
                        </div>
                        <div class="w-full md:w-1/2 lg:w-4/6 px-2">
                            <h3 class="border-b font-semibold text-sm border-info-tertiary py-2">{{ localization( 'Order Settings', 'NsGastro' ) }}</h3>
                            <div class="elevation-surface my-4 p-2">
                                <div class="-mx-2 flex flex-wrap">
                                    <div class="p-2 w-full md:w-1/2" :key="index" v-for="(field,index) of orderFields">
                                        <ns-field @change="detectOrderType( $event )" :field="field"></ns-field>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-2 flex justify-between border-t border-box-edge">
            <div></div>
            <div>
                <ns-button @click="submitOrderMerging()" type="info">{{ localization( 'Submit', 'NsGastro' ) }}</ns-button>
            </div>
        </div>
        <div v-if="isLoading" class="go-absolute go-flex go-items-center go-justify-center go-inset-0 go-bg-black go-opacity-50">
            <ns-spinner></ns-spinner>
        </div>
    </div>
    `,
    props: ['popup'],
    watch: {
        search() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.searchOrderWithQuery(this.search);
            }, 500);
        }
    },
    mounted() {
        this.popupCloser();
        this.$refs.searchField.focus();
        this.$refs.searchField.addEventListener('blur', () => {
            setTimeout(() => {
                this.searchResults = [];
            }, 300);
        });
        /**
         * we'll check if some orderrs has been selected for merge
         * if it's the case, well add them using addToTheQueue
         */
        if (Gastro.selectedOrdersSubject.getValue().length > 0) {
            Gastro.selectedOrdersSubject.getValue().forEach(order => {
                this.addToTheQueue(order);
            });
        }
        RxJS.forkJoin([
            this.loadCustomers(),
            this.loadOrderType(),
            this.loadTables(),
        ]).subscribe(result => {
            this.customers = result[0].map(customer => {
                return {
                    label: `${customer.first_name} ${customer.last_name}`,
                    value: customer.id
                };
            });
            this.orderTypes = Object.values(result[1].types).map(type => {
                return {
                    label: type.label,
                    value: type.identifier
                };
            });
            this.tables = result[2].map(table => {
                return {
                    label: table.name,
                    value: table.id
                };
            });
            this.buildFields();
        });
    },
    data() {
        return {
            search: '',
            searchResults: [],
            validation: new FormValidation,
            isLoading: false,
            orderFields: [],
            queuedOrders: [],
            customers: [],
            totalOrders: 0,
            totalProducts: 0,
            tables: [],
            orderTypes: [],
            typeLabels: {},
            mergeResult: {},
        };
    },
    methods: {
        localization: __m,
        popupCloser,
        popupResolver,
        nsCurrency,
        /**
         * We want to be able to detect wether
         * the order type is set to "dine-in" for injecting
         * tables.
         */
        detectOrderType(field) {
            if (field.name === 'type') {
                if (field.value === 'dine-in') {
                    /**
                     * @todo we need to make sure
                     * to skip this if the tables are disabled.
                     */
                    this.orderFields.push({
                        label: this.localization('Table', 'NsGastro'),
                        name: 'table_id',
                        type: 'select',
                        options: this.tables,
                        description: this.localization('Assign the order to a table.', 'NsGastro'),
                        validation: 'required'
                    });
                }
                else {
                    const field = this.orderFields.filter(f => f.name == 'table_id');
                    if (field.length > 0) {
                        const index = this.orderFields.indexOf(field[0]);
                        this.orderFields.splice(index, 1);
                    }
                }
            }
        },
        submitOrderMerging() {
            if (this.queuedOrders.length < 2) {
                return nsSnackBar.error(this.localization('There should be at least 2 queued orders for merging.', 'NsGastro')).subscribe();
            }
            if (this.queuedOrders.length > 5) {
                return nsSnackBar.error(this.localization('At most 5 orders can be merged.', 'NsGastro')).subscribe();
            }
            if (!this.validation.validateFields(this.orderFields)) {
                return nsSnackBar.error(this.localization('Unable to proceed the form is not valid.', 'NsGastro')).subscribe();
            }
            Popup.show(nsConfirmPopup, {
                title: this.localization('Confirm Your Action', 'NsGastro'),
                message: this.localization(`The provided order will be merged. Note that this operation can't be undone`, 'NsGastro'),
                onAction: (action) => {
                    if (action) {
                        this.proceedOrderMerging();
                    }
                }
            });
        },
        proceedOrderMerging() {
            this.isLoading = true;
            nsHttpClient.post(`/api/gastro/orders/merge`, {
                orders: this.queuedOrders,
                fields: this.validation.extractFields(this.orderFields)
            }).subscribe(result => {
                this.mergeResult = result.data;
                nsSnackBar.success(this.localization(`The orders has been merged into {order} successfully.`, 'NsGastro').replace('{order}', result.data.order.code), this.localization('Ok', 'NsGastro'), { duraton: 10000 }).subscribe();
                Gastro.selectedOrdersSubject.next([]);
                this.isLoading = false;
                this.popupResolver(true);
            }, (error) => {
                this.isLoading = false;
                nsSnackBar.error(this.localization('An unexpected error has occured.', 'NsGastro')).subscribe();
            });
        },
        removeOrderFromQueue(order) {
            const index = this.queuedOrders.indexOf(order);
            this.queuedOrders.splice(index, 1);
            Gastro.selectedOrdersSubject.next(this.queuedOrders);
            this.computeOrders();
        },
        buildFields() {
            this.orderFields = this.validation.createFields([
                {
                    label: this.localization('Customer', 'NsGastro'),
                    name: 'customer_id',
                    type: 'search-select',
                    options: this.customers,
                    validation: 'required',
                    description: this.localization('Assign a customer to the order.', 'NsGastro'),
                }, {
                    label: this.localization('Name', 'NsGastro'),
                    name: 'name',
                    type: 'text',
                    description: this.localization('Define the order name. Might be useful to retreive the order.', 'NsGastro'),
                }, {
                    label: this.localization('Order Type', 'NsGastro'),
                    name: 'type',
                    type: 'select',
                    options: this.orderTypes,
                    validation: 'required',
                    description: this.localization('Set what is the order type.', 'NsGastro'),
                }
            ]);
        },
        computeOrders() {
            if (this.queuedOrders.length > 0) {
                this.totalOrders = this.queuedOrders.map(order => order.total)
                    .reduce((before, after) => before + after);
                this.totalProducts = this.queuedOrders
                    .map(order => order.products.map(p => p.quantity).flat())
                    .flat()
                    .reduce((before, after) => before + after);
            }
            else {
                this.totalOrders = 0;
                this.totalProducts = 0;
            }
        },
        getOrderType(type) {
            return this.typeLabels[type] || this.localization('Unknown', 'NsGastro');
        },
        loadCustomers() {
            return nsHttpClient.get(`/api/customers`);
        },
        loadOrderType() {
            return nsHttpClient.get(`/api/gastro/order-types`);
        },
        /**
         * Will load all the orders
         * currently available on the system
         * @return void
         */
        loadTables() {
            return nsHttpClient.get(`/api/gastro/tables`);
        },
        addToTheQueue(order) {
            const ids = this.queuedOrders.map(order => order.id);
            this.search = '';
            if (ids.includes(order.id)) {
                return nsSnackBar.error(this.localization('The order has already been added to the queue.', 'NsGastro')).subscribe();
            }
            order.products = [];
            this.queuedOrders.push(order);
            nsHttpClient.get(`/api/gastro/orders/${order.id}/products`)
                .subscribe(products => {
                order.products = products;
                this.computeOrders();
            });
        },
        searchOrderWithQuery(term) {
            if (term.length > 0) {
                nsHttpClient.get(`/api/gastro/orders/search?search=${term}`)
                    .subscribe(result => {
                    if (result.length === 0) {
                        this.$refs.searchField.focus();
                        this.$refs.searchField.select();
                        nsSnackBar.info(this.localization('No results match your query, please try again.', 'NsGastro', 'OK', { duration: 4000 }))
                            .subscribe();
                    }
                    this.searchResults = result;
                }, (error) => {
                    return nsSnackBar.error(this.localization('An error has occured while searching orders', 'NsGastro'), 'OK', { duration: 4000 })
                        .subscribe();
                });
            }
        }
    }
};
//# sourceMappingURL=gastro-pos-merge.js.map