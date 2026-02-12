import { add } from './add.js';
import { getNext } from './add/next.js';
import { rout } from './rout.js';

let Orders = {
    listen: function () {
        document.addEventListener(
            'click',
            (event) => Orders.rout(
                event,
                Orders.getNextItemNumber,
                Orders.addOrderItem,
                Orders.removeOrderItem,
            )
        );
    },
    rout:              rout,
    getNextItemNumber: getNext,
    addOrderItem:      add,
    removeOrderItem: function (event) {
        event.target.closest('tr').remove();
    },
}

document.addEventListener('DOMContentLoaded', Orders.listen);
