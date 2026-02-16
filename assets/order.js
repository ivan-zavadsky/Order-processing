import { listen } from './order/listen.js';
import { rout } from './order/rout.js';
import { add } from './order/add.js';
import { remove } from './order/remove.js';
import { autocomplete } from './order/autocomplete/autocomplete.js';

export let Orders = {
    listen: function (event) {
        listen(event, Orders);
    },
    rout:                rout,
    addOrderItem:        add,
    removeOrderItem:     remove,
    productAutocomplete: autocomplete,
}

