import { getNext } from './add/next.js';

export
    function add(/*maxNumber*/) {
        const tr = document
            .querySelector('#order_items_0_product')
            .parentElement
            .parentElement
        ;
        let newTr = tr.cloneNode(true);
        let newSelect = newTr.querySelector('select');
        const maxNumber = getNext() + 1;
        // maxNumber++;
        newSelect.setAttribute(
            'id',
            'order_items_' + (maxNumber) + '_product'
        );
        newSelect.setAttribute(
            'name',
            'order[items][' + (maxNumber) + '][product]'
        );
        let newQuantity = newTr.querySelector('#order_items_0_quantity');
        newQuantity.setAttribute(
            'id',
            'order_items_' + (maxNumber) + '_quantity'
        );
        newQuantity.setAttribute(
            'name',
            'order[items][' + (maxNumber) + '][quantity]'
        );

        tr.parentElement.appendChild(newTr);
    }
