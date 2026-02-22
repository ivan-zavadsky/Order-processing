import { Orders } from "./order/order.js";
import { Polling } from "./polling/polling.js";

document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener(
        'click',
        (event) => Orders.rout(
                event,
                Orders
            )
    );

    if (window.location.pathname === '/order') {
        Polling.startPolling();
    } else {
        window.addEventListener('beforeunload', () => {
            Polling.stopPolling();
        });

    }

});
