import { rout } from './rout.js';
import { handleCheckAll, deleteSelectedOrders } from './checkbox.js';
import { sendSeveralOrders } from './send.js';

export let Orders = {
    rout:                 rout,
    handleCheckAll:       handleCheckAll,
    deleteSelectedOrders: deleteSelectedOrders,
    sendSeveralOrders:    sendSeveralOrders,
}

