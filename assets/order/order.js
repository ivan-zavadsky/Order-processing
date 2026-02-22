import { listen } from './listen.js';
import { rout } from './rout.js';
// import { add } from './add.js';
import { handleCheckAll, deleteSelectedOrders } from './checkbox.js';
import { sendSeveralOrders } from './send.js';

export let Orders = {
    listen: function (event) {
        listen(event, Orders);
    },
    rout:                 rout,
    // addOrderItem:         add,
    handleCheckAll:       handleCheckAll,
    deleteSelectedOrders: deleteSelectedOrders,
    sendSeveralOrders:    sendSeveralOrders,
}

