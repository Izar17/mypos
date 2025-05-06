import { Popup } from "~/libraries/popup";
import { default as nsCustomerSelectionPopup } from '~/popups/ns-pos-customer-select-popup.vue';
export class CustomerQueue {
    order;
    constructor(order) {
        this.order = order;
    }
    ;
    run() {
        return new Promise((resolve, reject) => {
            if (this.order.customer === undefined) {
                return Popup.show(nsCustomerSelectionPopup, { resolve, reject });
            }
            return resolve(true);
        });
    }
}
window.CustomerQueue = CustomerQueue;
//# sourceMappingURL=customer-queue.js.map