import { Popup } from "~/libraries/popup";
import { default as nsPaymentPopup } from '~/popups/ns-pos-payment-popup.vue';
export class PaymentQueue {
    order;
    constructor(order) {
        this.order = order;
    }
    run() {
        return new Promise((resolve, reject) => {
            Popup.show(nsPaymentPopup, { resolve, reject, order: this.order });
        });
    }
}
window.PaymentQueue = PaymentQueue;
//# sourceMappingURL=payment-queue.js.map