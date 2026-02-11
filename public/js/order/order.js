import { add } from './add.js';
import { getNext } from './add/next.js';

let Orders = {
    listen: function () {
        document.addEventListener('click', Orders.rout);
    },
    rout: function (event) {
        const action = event.target.dataset.action;
        switch (action) {
            case 'order-items#add':
                const maxNumber = Orders.getNextItemNumber();
                Orders.addOrderItem(maxNumber);
                break;
            case 'order-items#remove':
                Orders.removeOrderItem(event)
        }

    },
    getNextItemNumber: getNext,
    addOrderItem:      add,
    removeOrderItem: function (event) {
        event.target.closest('tr').remove();

    },
}

document.addEventListener('DOMContentLoaded', Orders.listen);
