/**
 * Роутер событий, который выполняет колбеки в зависимости от значения action в dataset
 * @param {Event} event - объект события
 * @param Orders
 */
export function rout(event, Orders) {
    const action = event.target.dataset.action;
    switch (action) {
        case 'order-items#add':
            Orders.addOrderItem();
            break;
        case 'order-items#remove':
            Orders.removeOrderItem(event);
            break;
        case 'check-all':
            Orders.handleCheckAll(event);
            break;
        case 'delete-selected':
            Orders.deleteSelectedOrders(event);
            break;
        case 'orders':
            Orders.sendSeveralOrders(event);
            break;
    }
}

