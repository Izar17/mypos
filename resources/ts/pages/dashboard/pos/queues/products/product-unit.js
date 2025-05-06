import { Popup } from "~/libraries/popup";
import nsProductUnitPopup from '~/popups/ns-pos-units.vue';
export class ProductUnitPromise {
    product;
    constructor(product) {
        this.product = product;
    }
    run() {
        return new Promise((resolve, reject) => {
            const product = this.product;
            Popup.show(nsProductUnitPopup, { resolve, reject, product });
        });
    }
}
//# sourceMappingURL=product-unit.js.map