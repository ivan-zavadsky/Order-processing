import { update } from './update.js';

/**
 * Функция для получения данных о заказах с сервера
 */
export function updateOrders() {
    return fetch('/api/order/all', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Error fetching orders');
        }
        return response.json();
    })
    .then(update)
    .catch(error => {
        console.error('Error updating orders:', error);
    });
}
