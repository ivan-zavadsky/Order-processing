<?php

namespace App\Service\Product;

use App\Repository\ProductRepository;

class SuggestionsProvider
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Получает подсказки продуктов по запросу
     *
     * @param string $query
     * @return array
     */
    public function get(string $query): array
    {
        // Получаем продукты через репозиторий
        $products = $this->productRepository->findHint($query);

        // Формируем массив названий продуктов
        $suggestions = array_map(function($product) {
            return $product->getName();
        }, $products);

        return $suggestions;
    }
}
