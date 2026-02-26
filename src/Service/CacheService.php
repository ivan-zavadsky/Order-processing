<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\RedisAdapter;

class CacheService
{
    private RedisAdapter $cache;

    public function __construct(RedisAdapter $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getCacheValue(string $key): ?string
    {
        $cacheItem = $this->cache->getItem($key);

        if (!$cacheItem->isHit()) {
            return null; // Значение не найдено в кеше
        }

        return $cacheItem->get(); // Возвращаем значение из кеша
    }

    /**
     * @throws InvalidArgumentException
     */
    public function setCacheValue(string $key, string $value): void
    {
        $cacheItem = $this->cache->getItem($key);
        $cacheItem->set($value);
        $this->cache->save($cacheItem); // Сохраняем элемент в кеш
    }
}
