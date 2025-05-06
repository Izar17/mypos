import * as Lodash from "lodash";
import Pusher from 'pusher-js';
import axios from "axios";
import * as ChartJS from "chart.js";
import { fromEvent } from "rxjs";
import * as RxJS from 'rxjs';
import { default as moment } from 'moment';
import { createApp } from "vue/dist/vue.esm-bundler";
import { Popup } from "~/libraries/popup";
import { EventEmitter, HttpClient, SnackBar, State, FloatingNotice } from "./libraries/libraries";
import FormValidation from "./libraries/form-validation";
import Url from "./libraries/url";
import countdown from "./libraries/countdown";
import CrudHandler from "./libraries/crud-handler";
import { createHooks } from '@wordpress/hooks';
import { __, __m } from "./libraries/lang";
import { insertAfterKey, insertBeforeKey } from "./libraries/object";
import popupResolver from "./libraries/popup-resolver";
import popupCloser from "./libraries/popup-closer";
import { timespan } from "./libraries/timespan";
import { defineAsyncComponent, defineComponent, markRaw, shallowRef } from "vue";
import { nsCurrency, nsRawCurrency } from "./filters/currency";
import { nsAbbreviate } from "./filters/abbreviate";
import { nsTruncate } from "./filters/truncate";
import Tax from "./libraries/tax";
;
window._ = Lodash;
window.ChartJS = ChartJS;
window.Pusher = Pusher;
window.createApp = createApp;
window.moment = moment;
window.Axios = axios;
window.__ = __;
window.__m = __m;
window.SnackBar = SnackBar;
window.FloatingNotice = FloatingNotice;
window.nsHooks = createHooks();
window.popupResolver = popupResolver,
    window.popupCloser = popupCloser,
    window.countdown = countdown;
window.timespan = timespan;
window.Axios.defaults.headers.common['x-requested-with'] = 'XMLHttpRequest';
window.Axios.defaults.withCredentials = true;
const nsEvent = new EventEmitter;
const nsHttpClient = new HttpClient;
const nsSnackBar = new SnackBar;
const nsNotice = new FloatingNotice;
const nsUrl = new Url;
const nsCrudHandler = new CrudHandler;
const nsHooks = window.nsHooks;
/**
 * create a screen class
 * that controls the device sizes.
 */
const nsScreen = new class {
    breakpoint;
    constructor() {
        this.breakpoint = '';
        this.detectScreenSizes();
        fromEvent(window, 'resize')
            .subscribe(v => this.detectScreenSizes());
    }
    detectScreenSizes() {
        switch (true) {
            case (window.outerWidth > 0) && (window.outerWidth <= 480):
                this.breakpoint = 'xs';
                break;
            case (window.outerWidth > 480) && (window.outerWidth <= 640):
                this.breakpoint = 'sm';
                break;
            case (window.outerWidth > 640) && (window.outerWidth <= 1024):
                this.breakpoint = 'md';
                break;
            case (window.outerWidth > 1024) && (window.outerWidth <= 1280):
                this.breakpoint = 'lg';
                break;
            case (window.outerWidth > 1280):
                this.breakpoint = 'xl';
                break;
        }
    }
};
const nsState = new State({
    sidebar: ['xs', 'sm', 'md'].includes(nsScreen.breakpoint) ? 'hidden' : 'visible'
});
nsHttpClient.defineClient(axios);
window.nsEvent = nsEvent;
window.nsHttpClient = nsHttpClient;
window.nsSnackBar = nsSnackBar;
window.nsNotice = nsNotice;
window.nsState = nsState;
window.nsUrl = nsUrl;
window.nsScreen = nsScreen;
window.ChartJS = ChartJS;
window.EventEmitter = EventEmitter;
window.Popup = Popup;
window.RxJS = RxJS;
window.FormValidation = FormValidation;
window.nsCrudHandler = nsCrudHandler;
window.defineComponent = defineComponent;
window.defineAsyncComponent = defineAsyncComponent;
window.markRaw = markRaw;
window.shallowRef = shallowRef;
window.createApp = createApp;
window.ns.insertAfterKey = insertAfterKey;
window.ns.insertBeforeKey = insertBeforeKey;
window.nsCurrency = nsCurrency;
window.nsAbbreviate = nsAbbreviate;
window.nsRawCurrency = nsRawCurrency;
window.nsTruncate = nsTruncate;
window.nsTax = Tax;
export { nsSnackBar, nsNotice, nsHttpClient, nsEvent, nsState, nsScreen, nsUrl, nsHooks };
//# sourceMappingURL=bootstrap.js.map