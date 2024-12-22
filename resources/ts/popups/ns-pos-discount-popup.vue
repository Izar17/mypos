<template>
    <!-- LCABORNAY -->
    <div id="numpad" class="flex justify-between ">

        <div class="ns-box shadow w-1/4 justify-end">
            <div @click="inputValue(key)" :key="index" v-for="(key, index) of discounts"
                    class="text-primary ns-numpad-key info text-xl font-bold border h-24 flex items-center justify-left cursor-pointer pl-2">
                    <i v-if="key.desc" >{{ key.desc }}</i>
                </div>
        </div>
    <!-- LCABORNAY -->
        <div id="discount-popup" class="ns-box shadow min-h-2/5-screen w-6/7-screen md:w-3/5-screen lg:w-3/5-screen xl:w-2/5-screen relative">
            <div class="flex-shrink-0 flex justify-between items-center p-2 border-b ns-box-header">
                <div>
                    <h1 class="text-xl font-bold text-primary text-center" v-if="type === 'product'">{{ __('Product Discount' ) }}</h1>
                    <h1 class="text-xl font-bold text-primary text-center" v-if="type === 'cart'">{{ __('Cart Discount'
                    ) }}</h1>
                </div>
                <div>
                    <ns-close-button @click="closePopup()"></ns-close-button>
                </div>
            </div>
            <div id="screen" class="h-16 ns-box-body text-white flex items-center justify-center">
                <h1 class="font-bold text-3xl">
                    <span v-if="mode === 'flat'">{{ nsCurrency(finalValue) }}</span>
                    <span v-if="mode === 'percentage'">{{ finalValue }}%</span>
                </h1>
            </div>
            <div id="switch-mode" class="flex">
                <button @click="setPercentageType('flat')"
                    :class="mode === 'flat' ? 'bg-tab-active' : 'bg-tab-inactive text-tertiary'"
                    class="outline-none w-1/2 py-2 flex items-center justify-center">{{ __('Flat') }}</button>
                <hr class="border-r border-box-edge">
                <button @click="setPercentageType('percentage')"
                    :class="mode === 'percentage' ? 'bg-tab-active' : 'bg-tab-inactive text-tertiary'"
                    class="outline-none w-1/2 py-2 flex items-center justify-center">{{ __('Percentage') }}</button>
            </div>
            <div id="numpad" class="grid grid-flow-row grid-cols-3 grid-rows-3">
                <div @click="inputValue(key)" :key="index" v-for="(key, index) of keys"
                    class="text-primary ns-numpad-key info text-xl font-bold border h-24 flex items-center justify-center cursor-pointer">
                    <span v-if="key.value !== undefined">{{ key.value }}</span>
                    <i v-if="key.icon" class="las" :class="key.icon"></i>
                </div>
            </div>
        </div>
        <!-- LCABORNAY -->
         <div class="ns-box shadow w-1/4 justify-end">
            <div class="h-16 ns-box-body text-white flex items-center justify-center">
                <h3 class="font-bold text-lg">{{ __('Number of PAX') }}
                </h3>
            </div>
            <div class="h-16 ns-box-body text-white flex items-center justify-center">
                <input v-model="numPax" type="number" maxlength="5"
                    class="h-16 w-11/12 text-center font-bold text-3xl text-black rounded-md no-border  focus:no-border focus:outline-none hover:no-border">
            </div>
            <div class="h-16 ns-box-body text-white flex items-center justify-center">
                <h3 class="font-bold text-lg">{{ __('PAX w/ Disc') }}
                </h3>
            </div>
            <div class="h-16 ns-box-body text-white flex items-center justify-center">
                <input v-model="numPaxDisc" type="number" maxlength="5"
                    class="h-16 w-11/12 text-center font-bold text-3xl text-black rounded-md no-border  focus:no-border focus:outline-none hover:no-border">
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { nsCurrency } from '~/filters/currency';
import { __ } from '~/libraries/lang';
import { Popup } from '~/libraries/popup';
import popupCloser from '~/libraries/popup-closer';
import popupResolver from '~/libraries/popup-resolver';
import nsManagerAuth from '~/popups/ns-pos-manager-auth.vue';

// LCABORNAY
class AuthManager {
    status: string;
    username?: string;
}

export default {
    name: 'ns-pos-discount-popup',
    props: [ 'popup' ],
    data() {
        return {
            finalValue: 1,
            virtualStock: null,
            popupSubscription: null,
            mode: '',
            type: '',
            allSelected: true,
            isLoading: false,
            keys: [
                ...([7,8,9].map( key => ({ identifier: key, value: key }))),
                ...([4,5,6].map( key => ({ identifier: key, value: key }))),
                ...([1,2,3].map( key => ({ identifier: key, value: key }))),
                ...[{ identifier: 'backspace', icon : 'la-backspace' },{ identifier: 0, value: 0 }, { identifier: 'next', icon: 'la-share' }],
            ], // LCABORNAY
            discounts: [
                { identifier: 'customDisc', value: 20, desc: 'SC' },
                { identifier: 'customDisc', value: 20, desc: 'PWD' },
                { identifier: 'customDisc', value: 10, desc: 'EMP' },
                { identifier: 'customDisc', value: 100, desc: 'FREE' },
            ],
            discountCode: '',
            numPax: 0,
            numPaxDisc: 0,
        }
    },
    mounted() {
        this.mode           =   this.popup.params.reference.discount_type || 'percentage';
        this.type           =   this.popup.params.type;

        if ( this.mode === 'percentage' ) {
            this.finalValue     =   this.popup.params.reference.discount_percentage || 1;
        } else {
            this.finalValue     =   this.popup.params.reference.discount || 1;
        }

        this.popupCloser();
    },
    methods: {
        __,
        nsCurrency,
        popupResolver,
        popupCloser,
        
        setPercentageType( mode ) {
            this.mode       =   mode;
        },
        closePopup() {
            this.popup.close();
        },

        async inputValue( key ) {
            if ( key.identifier === 'next' ) {
                // this.popup.params.onSubmit({
                //     discount_type           :   this.mode,
                //     discount_percentage     :   this.mode === 'percentage' ? this.finalValue : undefined,
                //     discount                :   this.mode === 'flat' ? this.finalValue : undefined
                // });
                // this.popup.close();
                // LCABORNAY
                try {
                    const response: AuthManager  = await new Promise((resolve, reject) => {
                        Popup.show(nsManagerAuth, { resolve, reject });
                    });

                    if (response.status === 'success') {
                        this.popup.params.onSubmit({
                            discount_type       : this.mode,
                            discount_percentage : this.mode === 'percentage' ? this.finalValue : undefined,
                            discount            : this.mode === 'flat' ? this.finalValue : undefined,
                            discount_manager    : response.username,
                            discount_code : this.discountCode,
                            number_pax : this.numPax,
                            number_pax_discount: this.numPaxDisc
                        });
                        this.popup.close();
                    }
                } catch (exception) {
                    console.log(exception)
                }
            } else if (key.identifier === 'customDisc') {
                this.finalValue = key.value;
                this.finalValue = parseFloat(this.finalValue);
                this.discountCode = key.desc;
                this.setPercentageType('percentage');
                // LCABORNAY
            } else if ( key.identifier === 'backspace' ) {
                if ( this.allSelected ) {
                    this.finalValue     =   0;
                    this.allSelected    =   false;
                } else {
                    this.finalValue     =   this.finalValue.toString();
                    this.finalValue     =   this.finalValue.substr(0, this.finalValue.length - 1 ) || 0;
                }
            } else {
                if ( this.allSelected ) {
                    this.finalValue     =   key.value;
                    this.finalValue     =   parseFloat( this.finalValue );
                    this.allSelected    =   false;
                } else {
                    this.finalValue     +=  '' + key.value;
                    this.finalValue     =   parseFloat( this.finalValue );

                    if ( this.mode === 'percentage' ) {
                        this.finalValue = this.finalValue > 100 ? 100 : this.finalValue;
                    }
                }
            } 
        }
    }
}
</script>