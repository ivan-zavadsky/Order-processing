/**
 * Роутер событий, который выполняет колбеки в зависимости от значения action в dataset
 * @param {Event} event - объект события
 * @param {Function} getNextNumber - функция для получения следующего номера
 * @param {Function} addItem - функция для добавления элемента
 * @param {Function} removeItem
 */
export function rout(event, /*getNextNumber,*/ addItem, removeItem) {
    const action = event.target.dataset.action;
    switch (action) {
        case 'order-items#add':
            // const maxNumber = getNextNumber();
            addItem(/*maxNumber*/);
            break;
        case 'order-items#remove':
            removeItem(event);
    }
}

