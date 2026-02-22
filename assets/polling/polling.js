import { startPolling } from './start.js';
import { updateOrders } from './fetch.js';

export let Polling = {
    updateOrders:         updateOrders,
    startPolling: function() {
        if (!this.polling) {
            this.polling = startPolling(
                () => this.updateOrders(),
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
