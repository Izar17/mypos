import { nsSnackBar } from "~/bootstrap";
import { __ } from "~/libraries/lang";
export class ProductsQueue {
    order;
    constructor(order) {
        this.order = order;
    }
    run() {
        return new Promise((resolve, reject) => {
            if (this.order.products.length === 0) {
                nsSnackBar.error(__('You need to provide some products before proceeding.')).subscribe();
                return reject(false);
            }
            return resolve(true);
        });
    }
}
window.ProductsQueue = ProductsQueue;
//# sourceMappingURL=products-queue.js.map