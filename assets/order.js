import { listen } from './order/listen.js';
import { rout } from './order/rout.js';
import { add } from './order/add.js';
import { remove } from './order/remove.js';
import { autocomplete } from './order/autocomplete/autocomplete.js';
import { handleCheckAll, deleteSelectedOrders } from './order/checkbox.js';
import { sendSeveralOrders } from './order/send.js';

export let Orders = {
    listen: function (event) {
        listen(event, Orders);
    },
    rout:                rout,
    addOrderItem:        add,
    removeOrderItem:     remove,
    productAutocomplete: autocomplete,
    handleCheckAll:      handleCheckAll,
    deleteSelectedOrders: deleteSelectedOrders,
    sendSeveralOrders:   sendSeveralOrders,
}

