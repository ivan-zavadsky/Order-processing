/**
 * Обработчик для отправки нескольких заказов
 */
export function sendSeveralOrders(event) {
    event.preventDefault();

    let orders = [];
    let maxOrdersNumber = Math.floor(Math.random() * (5)) + 1;
    for (let i = 1; i <= maxOrdersNumber; i++) {
        let userId = Math.floor(Math.random() * (100)) + 1;
        let items = [];
        let validProductIds = [54, 55, 56, 57, 58, 59];
        let maxProductsNumber = Math.floor(Math.random() * (3)) + 1
        for (let j = 1; j <= maxProductsNumber; j++) {
            let productId = validProductIds[Math.floor(Math.random()*validProductIds.length)];
            let quantity = Math.floor(Math.random() * (5)) + 1;
            items.push({
                "productId": productId,
                "quantity": quantity
            });
        }
        orders.push({
            "userId": userId,
            "items": items
        });
    }

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
