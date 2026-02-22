export function prepareOrders() {
    let orders = [];
    let maxOrdersNumber = Math.floor(Math.random() * (5)) + 1;
    for (let i = 1; i <= maxOrdersNumber; i++) {
        let userId = Math.floor(Math.random() * (7)) + 1;
        let items = [];
        let validProductIds = [1, 2, 3, 4, 5, 6];
        let maxProductsNumber = Math.floor(Math.random() * (6)) + 1
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

    return orders;
}
