
/**
 * Обработчик для управления чекбоксами в таблице заказов
 */
export function handleCheckAll(event) {
    const checkAllCheckbox = event.target;
    const checkboxes = document.querySelectorAll('input[name="ids[]"]');

    checkboxes.forEach(checkbox => {
        checkbox.checked = checkAllCheckbox.checked;
    });
}
