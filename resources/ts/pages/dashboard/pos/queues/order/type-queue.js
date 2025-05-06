import { Popup } from "~/libraries/popup";
import orderTypePopup from '~/popups/ns-pos-order-type-popup.vue';
export class TypeQueue {
    order;
    constructor(order) {
        this.order = order;
    }
    run() {
        return new Promise((resolve, reject) => {
            if (this.order.type === undefined) {
                return Popup.show(orderTypePopup, { resolve, reject });
            }
            resolve(true);
        });
    }
}
window.TypeQueue = TypeQueue;
//# sourceMappingURL=type-queue.js.map