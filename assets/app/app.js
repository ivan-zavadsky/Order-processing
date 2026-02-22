import { rout } from './rout.js';
import { Orders } from '../order/order.js';
import { Polling } from '../polling/polling.js';
export let App = {
    rout:                 rout,
    listen: function () {
        document.addEventListener(
            'click',
            (event) => this.rout(
                event,
                Orders
            )
        );
    },
    poll: function () {
        if (window.location.pathname === '/order') {
            Polling.startPolling();
        } else {
            window.addEventListener('beforeunload', () => {
                Polling.stopPolling();
            });

        }
    },
};
