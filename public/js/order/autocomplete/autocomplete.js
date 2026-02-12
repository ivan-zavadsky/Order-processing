import { container } from "./suggestionsContainer";

export
function autocomplete() {
    // Создаем контейнер для подсказок
    const suggestionsContainer = container;
    document.body.appendChild(suggestionsContainer);

    let currentInput = null;
    let debounceTimer = null;

    // Функция для получения подсказок
    const fetchSuggestions = async (query) => {
        try {
            const response =
                await fetch(
                    `/product/hint?q=${encodeURIComponent(query)}`
                );
            const data = await response.json();

            return data;
        } catch (error) {
            console.error('Ошибка при получении подсказок:', error);
            return [];
        }
    };

    // Функция для показа подсказок
    const showSuggestions = (suggestions, input) => {
        currentInput = input;
        const rect = input.getBoundingClientRect();
        suggestionsContainer.style.left = `${rect.left}px`;
        suggestionsContainer.style.top = `${rect.bottom + window.scrollY}px`;
        suggestionsContainer.style.width = `${rect.width}px`;
        suggestionsContainer.innerHTML = '';

        if (suggestions.length === 0) {
            suggestionsContainer.style.display = 'none';
            return;
        }

        suggestions.forEach(suggestion => {
            const item = document.createElement('div');
            item.className = 'suggestion-item';
            item.textContent = suggestion;
            item.style.cssText = `
                    padding: 8px 12px;
                    cursor: pointer;
                    transition: background 0.2s;
                `;
            item.addEventListener('mouseenter', () => {
                item.style.background = '#f0f0f0';
            });
            item.addEventListener('mouseleave', () => {
                item.style.background = 'white';
            });
            item.addEventListener('click', () => {
                if (currentInput) {
                    currentInput.value = suggestion;
                    suggestionsContainer.style.display = 'none';
                }
            });
            suggestionsContainer.appendChild(item);
        });

        suggestionsContainer.style.display = 'block';
    };

    // Добавляем обработчик событий для полей продукта
    document.addEventListener('input', async (event) => {
        const input = event.target;
        // Проверяем, что это поле ввода продукта
        if (input.tagName === 'INPUT' && input.name && input.name.includes('[product]')) {
            const query = input.value.trim();

            // Скрываем подсказки, если поле пустое
            if (query.length === 0) {
                suggestionsContainer.style.display = 'none';
                return;
            }

            // Используем debounce для уменьшения количества запросов
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(async () => {
                const suggestions = await fetchSuggestions(query);
                showSuggestions(suggestions, input);
            }, 300);
        }
    });

    // Скрываем подсказки при клике вне контейнера
    document.addEventListener('click', (event) => {
        if (!suggestionsContainer.contains(event.target) && event.target !== currentInput) {
            suggestionsContainer.style.display = 'none';
        }
    });

    // Скрываем подсказки при нажатии Escape
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            suggestionsContainer.style.display = 'none';
        }
    });
}
