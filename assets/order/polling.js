/**
 * Модуль для периодического обновления данных на странице заказов
 */
export function startPolling(callback, interval = 3000) {
    let pollingInterval;

    // Функция для запуска polling
    function start() {
        // Сразу вызываем callback для получения данных
        callback();

        // Устанавливаем интервал для периодического обновления
        pollingInterval = setInterval(callback, interval);
    }

    // Функция для остановки polling
    function stop() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    return {
        start,
        stop
    };
}
