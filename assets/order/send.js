import { prepareOrders } from './prepare_orders.js';

/**
 * Обработчик для отправки нескольких заказов
 */
export function sendSeveralOrders(event) {
    event.preventDefault();

    let orders = prepareOrders();
    // Отправляем каждый заказ на сервер
    orders.forEach(order => {
        fetch('/api/order/new', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(order)
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error('Error sending order');
            }
        })
        .then(data => {
            console.log('Order sent successfully:', data);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error sending order');
        });
    });
}
