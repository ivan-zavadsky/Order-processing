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
                colspan="5"
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

        let type;
        switch (order.status) {
            case 'new':
                type = 'primary';
                break;
            case 'modified':
                type = 'secondary';
                break;
            case 'failed':
                type = 'dark';
                break;
            case 'processing':
                type = 'secondary';
                break;
        }

        order.userName = order.userName
            ? order.userName
            : 'Неизвестный пользователь'
        ;

        let statusTd;
        switch (order.status) {
            case 'modified':
                statusTd =
                    `<span
                        class="badge bg-secondary position-relative"
                    >
                        <h6>${order.status}</h6>
                        <span
                            class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                      </span>
                    </span>`;
                break;
            default:
                statusTd = `
                    <span class="badge bg-${type}">
                        <h6>${order.status}</h6>
                    </span>
                `;
        }

        row.innerHTML = `
            <td>
                <label>
                    <input
                        type="checkbox"
                        name="ids[]"
                        value="${order.id}"
                        ${ selectedIds.includes(String(order.id))
                            ? 'checked'
                            : '' }
                    >
                </label>
            </td>
            <td>${order.userName}</td>
            <td>${order.id}</td>
            <td class="text-center">
                ${ statusTd }
            </td>
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
