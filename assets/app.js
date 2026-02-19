import {Orders} from "./order.js";

document.addEventListener('DOMContentLoaded', () => {
    Orders.listen();
    // Orders.startPolling();
    if (window.location.pathname === '/order') {
        Orders.startPolling();
    } else {
        window.addEventListener('beforeunload', () => {
            Orders.stopPolling();
        });

    }

});
