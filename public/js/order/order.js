import { listen } from './listen.js';
import { rout } from './rout.js';
import { add } from './add.js';
import { remove } from './remove.js';

let Orders = {
    listen: function (event) {
        listen(event, Orders);
    },
    rout:              rout,
    addOrderItem:      add,
    removeOrderItem:   remove,
}

document.addEventListener('DOMContentLoaded', Orders.listen);
