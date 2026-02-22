/**
 * Обработчик для удаления выбранных заказов
 */
export function deleteSelectedOrders(event) {
    event.preventDefault();

    // Находим все выбранные чекбоксы
    const selectedCheckboxes = document
        .querySelectorAll('input[name="ids[]"]:checked')
    ;

    if (selectedCheckboxes.length === 0) {
        alert('Please select at least one order to delete');
        return;
    }

    // Собираем ID выбранных заказов
    const orderIds = Array
        .from(selectedCheckboxes)
        .map(checkbox => checkbox.value)
    ;

    // Отправляем запрос на сервер для удаления
    fetch('/order/delete-selected', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ ids: orderIds })
    })
    .then(response => {
        if (response.ok) {
            // Удаляем строки из таблицы
            selectedCheckboxes.forEach(checkbox => {
                checkbox.closest('tr').remove();
            });
            // Сбрасываем чекбокс "выбрать все"
            document.getElementById('check-all').checked = false;
        } else {
            alert('Error deleting orders');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error deleting orders');
    });
}
