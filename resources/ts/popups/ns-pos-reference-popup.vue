<!-- LCABORNAY -->
<template>
    <div id="reference-popup"
        class="ns-box shadow min-h-2/6-screen w-6/7-screen md:w-3/5-screen lg:w-3/5-screen xl:w-2/5-screen relative">
        <div class="flex-shrink-0 flex justify-between items-center p-2 border-b ns-box-header">
            <div>
                <h1 class="text-xl font-bold text-primary text-center">{{ __('Reference No.') }}</h1>
            </div>
            <div>
                <ns-close-button @click="closePopup()"></ns-close-button>
            </div>
        </div>
        <div class="h-16 ns-box-body text-white flex w-full items-center justify-center my-2">
            <input v-model="finalValue" ref="reference" type="text" maxlength="40"
                class="h-16 w-11/12 text-center font-bold text-3xl text-black rounded-md no-border  focus:no-border focus:outline-none hover:no-border">
        </div>

        <div class="ns-box-body flex w-full items-center justify-center">
            <button @click="inputValue()"
                class="h-16 w-11/12 flex items-center justify-center text-4xl font-bold rounded-xl bg-sky-400 hover:bg-sky-500 text-white">
                <span class="text-lg">{{ __('Submit') }}</span>
            </button>
        </div>
    </div>
</template>
<script>
import { __ } from '~/libraries/lang';
import popupCloser from '~/libraries/popup-closer';
export default {
    name: 'ns-pos-discount-popup',
    props: [ 'popup' ],
    data() {
        return {
            finalValue: '',
            allSelected: true,
            isLoading: false,
        }
    },
    mounted() {
        this.$refs.reference.focus()

        // this.$popup.event.subscribe((action) => {
        //     if (action.event === 'click-overlay') {
        //         this.popup.close();
        //     }
        // })
        this.popupCloser();
    },
    methods: {
        __,
        popupCloser,
        closePopup() {
            this.popup.close();
        },
        inputValue() {
            this.popup.params.onSubmit({
                reference: this.finalValue
            });
            this.popup.close();
        }
    }
}
</script>