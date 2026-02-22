import { handleCheckAll } from './checkbox.js';
import { deleteSelectedOrders } from './delete_selected.js';
import { sendSeveralOrders } from './send.js';

export let Orders = {
    handleCheckAll:       handleCheckAll,
    deleteSelectedOrders: deleteSelectedOrders,
    sendSeveralOrders:    sendSeveralOrders,
}

