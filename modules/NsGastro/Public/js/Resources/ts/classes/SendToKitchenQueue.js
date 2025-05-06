export class SendToKitchenQueue {
    order;
    constructor(order) {
        this.order = order;
    }
    run() {
        return new Promise((resolve, reject) => {
            Popup.show(nsConfirmPopup, {
                title: 'Send the order to the kitchen',
                message: `An order send to the kitchen can be seen and cooked by kitchen staff.`,
                onAction: (action) => {
                    if (action) {
                        this.order.gastro_order_status = 'pending';
                        resolve(true);
                    }
                    else {
                        this.order.gastro_order_status = 'hold';
                        reject(false);
                    }
                }
            });
        });
    }
}
//# sourceMappingURL=SendToKitchenQueue.js.map