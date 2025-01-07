<template>
    <div id="report-section" class="px-4">
        <div class="flex -mx-2">
            <div class="px-2">
                <ns-date-time-picker :field="startDateField"></ns-date-time-picker>
            </div>
            <!-- <div class="px-2">
                <button @click="openUserFiltering()"
                    class="rounded flex justify-between bg-input-button shadow py-1 items-center text-primary px-2">
                    <i class="las la-filter text-xl"></i>
                    <span class="pl-2">{{ __("By User") }} : {{ selectedUser || __("All Users") }}</span>
                </button>
            </div> -->
            <div class="px-2">
                <button @click="loadReport()"
                    class="rounded flex justify-between bg-input-button shadow py-1 items-center text-primary px-2">
                    <i :class="isLoading ? 'animate-spin' : ''" class="las la-sync-alt text-xl"></i>
                    <span class="pl-2">{{ __("Load") }}</span>
                </button>
            </div>
            <div class="px-2">
                <button @click="printSaleReport()"
                    class="rounded flex justify-between bg-input-button shadow py-1 items-center text-primary px-2">
                    <i class="las la-print text-xl"></i>
                    <span class="pl-2">{{ __("Print") }}</span>
                </button>
            </div>
        </div>
        <div>
            
            <div id="sale-report" class="anim-duration-500 fade-in-entrance">
            <div class="w-full h-full pt-5" v-if="result.store_name">
                <div class="w-full md:w-1/2 lg:w-1/3 shadow-lg bg-white p-2 mx-auto">
                    <div class="flex items-center justify-center">
                        <p class="text-sm font-bold">{{ result.store_name }}</p>
                    </div>
                    <div class="flex items-center justify-center">
                        <p class="text-sm font-bold">{{ result.title }}</p>
                    </div>
                    <div class="flex items-center justify-center">
                        <p class="text-sm font-bold">{{ result.date }}</p>
                    </div>
                    <div class="p-2">
                        <div class="flex flex-wrap -mx-2 text-xs"></div>
                    </div>
                    <div class="table w-full text-xs" :set="(vat_12 =
                    parseFloat(result?.orders?.vatable) +
                    parseFloat(result?.orders?.sc_vatable) -
                    (parseFloat(result?.orders?.vatable) +
                        parseFloat(result?.orders?.sc_vatable)) /
                    1.12)
                    ">
                        <table class="w-full">
                            <tr v-if="result.cashier">
                                <td class="text-xs">{{ __("Cashier Name:") }}</td>
                                <td colspan="2" class="text-left">{{ result?.cashier }}</td>
                            </tr>

                            <tr class="border-b"></tr>
                            <tbody class="text-xs">
                                <tr>
                                    <td class=" ">{{ __("TOTAL SALES") }}</td>
                                    <td class="text-right">
                                        {{ result?.orders?.total_sales_count }}
                                    </td>
                                    <td class="text-right">
                                        {{ nsCurrency(result?.orders?.total_sales) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class=" ">{{ __("LESS TOTAL REFUND") }}</td>
                                    <td class="text-right">
                                        {{ result?.orders?.total_refunds_count }}
                                    </td>
                                    <td class="text-right">
                                        {{ nsCurrency(result?.orders?.total_refunds) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class=" ">{{ __("LESS TOTAL DISCOUNT") }}</td>
                                    <td class="text-right">
                                        {{ result?.orders?.total_disc_count }}
                                    </td>
                                    <td class="text-right">
                                        {{ nsCurrency(result?.orders?.total_disc) }}
                                    </td>
                                </tr>

                                <tr class="border-b"></tr>
                                <tr>
                                    <td class=" ">{{ __("SUBTOTAL") }}</td>
                                    <td class="text-right">
                                        {{ result?.orders?.total_sales_count }}
                                    </td>
                                    <td class="text-right">
                                        {{ nsCurrency(result?.orders?.subtotal) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class=" ">{{ __("SERVICE CHARGE") }}</td>
                                    <td></td>
                                    <td class="text-right">
                                        {{ nsCurrency(result?.orders?.service_charge) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class=" ">{{ __("LESS 12% VAT") }}</td>
                                    <td></td>
                                    <td class="text-right">{{ nsCurrency(vat_12 || 0) }}</td>
                                </tr>
                                <tr>
                                    <td class=" ">{{ __("NET SALES") }}</td>
                                    <td></td>
                                    <td class="text-right">
                                        {{ nsCurrency(parseFloat(result?.orders?.total - vat_12) || 0) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class=" ">{{ __("VATable Sales") }}</td>
                                    <td></td>
                                    <td class="text-right">
                                        {{
                    nsCurrency(
                        parseFloat(result?.orders?.vatable) +
                        parseFloat(result?.orders?.sc_vatable) || 0
                    )
                }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class=" ">{{ __("VAT-Exempt Sales") }}</td>
                                    <td></td>
                                    <td class="text-right">
                                        {{
                    nsCurrency(parseFloat(result?.orders?.vat_exempt) || 0)
                }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class=" ">{{ __("VAT Zero-Rated Sales") }}</td>
                                    <td></td>
                                    <td class="text-right">0</td>
                                </tr>
                                <tr class="border-b"></tr>
                                <tr>
                                    <td class=" ">{{ __("TERMINAL TOTAL") }}</td>
                                    <td></td>
                                    <td class="text-right">
                                        {{ nsCurrency(parseFloat(result?.orders?.total) || 0) }}
                                    </td>
                                </tr>

                                <tr>
                                    <td class=" ">{{ __("TRANSACTIONS") }}</td>
                                </tr>
                                <tr>
                                    <td class="pl-3">{{ __("Dine In") }}</td>
                                    <td class="text-right">{{ result?.orders?.dinein_count }}</td>
                                    <td class="text-right">
                                        {{
                    nsCurrency(parseFloat(result?.orders?.dinein_total) || 0)
                }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pl-3">{{ __("Take Away") }}</td>
                                    <td class="text-right">
                                        {{ result?.orders?.takeaway_count }}
                                    </td>
                                    <td class="text-right">
                                        {{
                    nsCurrency(
                        parseFloat(result?.orders?.takeaway_total) || 0
                    )
                }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class=" ">{{ __("TENDERS") }}</td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr v-for="payment in result?.payments">
                                    <td class="pl-3">{{ payment.label }}</td>
                                    <td class="text-right">{{ payment.count }} </td>
                                    <td class="text-right">{{ nsCurrency(parseFloat(payment.total)) }}  </td>
                                </tr>
                                <tr>
                                    <td class=" ">{{ __("DISCOUNTS") }}</td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class="pl-3">{{ __("SC") }}</td>
                                    <td class="text-right">
                                        {{ result?.orders?.sc_count_disc }}
                                    </td>
                                    <td class="text-right">
                                        {{
                    nsCurrency(parseFloat(result?.orders?.sc_total_disc) || 0)
                }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pl-3">{{ __("PWD") }}</td>
                                    <td class="text-right">
                                        {{ result?.orders?.pwd_count_disc }}
                                    </td>
                                    <td class="text-right">
                                        {{
                    nsCurrency(
                        parseFloat(result?.orders?.pwd_total_disc) || 0
                    )
                }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pl-3">{{ __("EMP") }}</td>
                                    <td class="text-right">
                                        {{ result?.orders?.emp_count_disc }}
                                    </td>
                                    <td class="text-right">
                                        {{
                    nsCurrency(
                        parseFloat(result?.orders?.emp_total_disc) || 0
                    )
                }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pl-3">{{ __("FREE") }}</td>
                                    <td class="text-right">
                                        {{ result?.orders?.free_count_disc }}
                                    </td>
                                    <td class="text-right">
                                        {{
                    nsCurrency(
                                        parseFloat(result?.orders?.free_total_disc) || 0
                                        )
                                        }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pl-3">{{ __("REG") }}</td>
                                    <td class="text-right">
                                        {{ result?.orders?.reg_count_disc }}
                                    </td>
                                    <td class="text-right">
                                        {{
                                        nsCurrency(
                                        parseFloat(result?.orders?.reg_total_disc) || 0
                                        )
                                        }}
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <td class=" ">{{ __("TOTAL QUANTITY SOLD") }}</td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr>
                                    <td class=" ">{{ __("TOTAL TRANSACTIONS") }}</td>
                                    <td class="text-right"></td>
                                </tr> -->
                                <!-- <tr>
                                    <td  class=" "> {{ __('TOTAL NUMBE PAX') }}</td>
                                    <td class="text-right">test1</td>
                                </tr> -->
                                <tr class="border-b"></tr>
                                <tr>
                                    
                                    <td  colspan="3"  class="text-center">{{ result?.generated_date }}</td>
                                </tr> 
                            </tbody>
                        
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from "moment";
import { nsHttpClient, nsSnackBar } from "~/bootstrap";
import { default as nsDateTimePicker } from "~/components/ns-date-time-picker.vue";
import nsDatepicker from "~/components/ns-datepicker.vue";
import { nsCurrency } from "~/filters/currency";
import { joinArray } from "~/libraries/join-array";
import { __ } from "~/libraries/lang";
import nsSelectPopupVue from "~/popups/ns-select-popup.vue";

export default {
    name: "ns-zreading-report",
    data() {
        return {
            saleReport: "",
            startDateField: {
                name: "start_date",
                type: "datetime",
                value: ns.date.moment.startOf("day").format(),
            },
            // endDateField: {
            //     name: 'end_date',
            //     type: 'datetime',
            //     value: ns.date.moment.endOf('day').format()
            // },
            result: {},
            isLoading: false,
            users: [],
            ns: window.ns,
            // summary: {},
            selectedUser: "",
            // selectedCategory: '',
            // reportType: {
            //     label: __('Report Type'),
            //     name: 'reportType',
            //     type: 'select',
            //     value: 'categories_report',
            //     options: [
            //         {
            //             label: __('Categories Detailed'),
            //             value: 'categories_report',
            //         }, {
            //             label: __('Categories Summary'),
            //             value: 'categories_summary',
            //         }, {
            //             label: __('Products'),
            //             value: 'products_report',
            //         }
            //     ],
            //     description: __('Allow you to choose the report type.'),
            // },
            filterUser: {
                label: __("Filter User"),
                name: "filterUser",
                type: "select",
                value: "",
                options: [
                    // ...
                ],
                description: __("Allow you to choose the report type."),
            },
            // filterCategory: {
            //     label: __('Filter By Category'),
            //     name: 'filterCategory',
            //     type: 'multiselect',
            //     value: '',
            //     options: [
            //         // ...
            //     ],
            //     description: __('Allow you to choose the category.'),
            // },
            field: {
                type: "datetimepicker",
                value: "2021-02-07",
                name: "date",
            },
        };
    },
    components: {
        nsDatepicker,
        nsDateTimePicker,
    },
    computed: {
        // ...
    },
    methods: {
        __,
        nsCurrency,
        joinArray,
        printSaleReport() {
            this.$htmlToPaper("sale-report");
        },
        async openUserFiltering() {
            try {
                /**
                 * let's try to pull the users first.
                 */
                this.isLoading = true;
                const result = await new Promise((resolve, reject) => {
                    nsHttpClient.get(`/api/users`).subscribe({
                        next: (users) => {
                            this.users = users;
                            this.isLoading = false;

                            this.filterUser.options = [
                                {
                                    label: __("All Users"),
                                    value: "",
                                },
                                ...this.users.map((user) => {
                                    return {
                                        label: user.username,
                                        value: user.id,
                                    };
                                }),
                            ];

                            Popup.show(nsSelectPopupVue, {
                                ...this.filterUser,
                                resolve,
                                reject,
                            });
                        },
                        error: (error) => {
                            this.isLoading = false;
                            nsSnackBar.error(
                                __("No user was found for proceeding the filtering.")
                            );
                            reject(error);
                        },
                    });
                });

                const searchUser = this.users.filter((__user) => __user.id === result);

                if (searchUser.length > 0) {
                    let user = searchUser[0];
                    this.selectedUser = `${user.username} ${user.first_name || user.last_name
                        ? user.first_name + " " + user.last_name
                        : ""
                        }`;
                    this.filterUser.value = result;
                    this.result = [];
                    this.loadReport();
                }
            } catch (exception) {
                console.log({ exception });
            }
        },

        loadReport() {
            if (this.startDate === null) {
                return nsSnackBar
                    .error(__("Unable to proceed. Select a correct time range."))
                    .subscribe();
            }

            const startMoment = moment(this.startDate);
            // const endMoment = moment(this.endDate);

            // if (endMoment.isBefore(startMoment)) {
            //     return nsSnackBar.error(__('Unable to proceed. The current time range is not valid.')).subscribe();
            // }

            this.isLoading = true;
            nsHttpClient
                .post("/api/reports/xzreading-report", {
                    startDate: this.startDateField.value,
                    // endDate: this.endDateField.value,
                    // type: this.reportType.value,
                    user_id: this.filterUser.value,
                    // categories_id: this.filterCategory.value
                })
                .subscribe({
                    next: (response) => {
                        this.isLoading = false;
                        this.result = response.result;
                        // this.summary = response.summary;
                        console.log(this.result);
                    },
                    error: (error) => {
                        this.isLoading = false;
                        nsSnackBar.error(error.message).subscribe();
                    },
                });
        },
    },
    props: ["storeLogo", "storeName"],
    mounted() {
        // ...
    },
};
</script>
