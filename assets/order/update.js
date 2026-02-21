export function update(data) {
    const tbody = document.querySelector('table tbody');
    if (!tbody) return;

    // Сохраняем состояние выбранных чекбоксов
    const selectedIds = Array.from(document.querySelectorAll('input[name="ids[]"]:checked'))
        .map(checkbox => checkbox.value);

    // Очищаем таблицу
    tbody.innerHTML = '';

    // Если нет заказов, показываем сообщение
    if (data.length === 0) {
        tbody.innerHTML =
            `<tr><td
                colspan="4"
                class="text-center text-muted"
            >
                no records found
            </td></tr>`;
        return;
    }

    // Добавляем строки с данными
    data.forEach(order => {
        const row = document.createElement('tr');

        // Формируем HTML для товаров
        const itemsHtml = order.items.map(item =>
            `${item.productName} x ${item.quantity}<br>`
        ).join('');

        row.innerHTML = `
            <td>
                <label>
                    <input type="checkbox" name="ids[]" value="${order.id}" ${selectedIds.includes(String(order.id)) ? 'checked' : ''}>
                </label>
            </td>
            <td>${order.id}</td>
            <td>${order.status}</td>
            <td>${ order.userName }</td>
            <td class="text-end">
                <a
                    href="/order/${order.id}"
                    class="btn btn-sm btn-secondary me-1"
                >
                    Show
                </a>
            </td>
        `;

        tbody.appendChild(row);
    });
}
