/**
 * @param {Event} event - объект события
 * @param Orders
 */
export function listen(event, Orders) {
    document.addEventListener(
        'click',
        (event) => Orders.rout(
            event,
            Orders
        )
    );
}

