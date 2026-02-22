import {Orders} from "./order/order.js";
import { startPolling } from './order/polling.js';
import { updateOrders } from './order/fetch.js';

let App = {
    updateOrders:         updateOrders,
    startPolling: function() {
        if (!this.polling) {
            this.polling = startPolling(
                () => App.updateOrders(),
                3000
            );
            this.polling.start();
        }
    },
    stopPolling: function() {
        if (this.polling) {
            this.polling.stop();
            this.polling = null;
        }
    }
};
document.addEventListener('DOMContentLoaded', () => {
    Orders.listen();
    if (window.location.pathname === '/order') {
        App.startPolling();
    } else {
        window.addEventListener('beforeunload', () => {
            App.stopPolling();
        });

    }

});
