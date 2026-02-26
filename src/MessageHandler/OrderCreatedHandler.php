<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Entity\OrderItem;
use App\Enum\OrderStatus;
use App\Message\OrderCreatedMessage;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\CacheService;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use stdClass;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class OrderCreatedHandler
{
    public function __construct(
        private OrderRepository $orderRepository,
        private ProductRepository $productRepository,
        private CacheService $cache,
        #[Autowire(service: 'monolog.logger.my_channel')]
        private readonly LoggerInterface $myLogger,
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    public function __invoke(
        OrderCreatedMessage $message
    )
        : void
    {
        $order = $this->orderRepository
            ->findObjectWithRelations($message->orderId);
        if ($order->getStatus() !== OrderStatus::NEW) {
            return;
        }
        $order->setStatus(OrderStatus::PROCESSING);
        $this->orderRepository->save($order);

        $dto = new stdClass();
        $dto->status = $order->getStatus();
        $dto->userName = $order->getUser()->getName();
        foreach ($order->getItems() as $item) {
            $orderItem = new stdClass();
            $product = $this->productRepository
                ->findOneById($item->getProduct()->getId());
            if (!$product) {
                $order->setStatus(OrderStatus::MODIFIED);
                $this->orderRepository->save($order);
                $dto->status = $order->getStatus();
                continue;
//                throw new \Exception('Product id='
//                    . $item->productId . ' not found');
            }

            $orderItem->productName = $product->getName();
            $orderItem->quantity = $item->getQuantity();
            $orderItem->price = $item->getPrice();
            $dto->items[] = $orderItem;
        }
        $dto->id = $order->getId();

        $cacheKey = 'order_' . $order->getId();
        $this->cache->setCacheValue($cacheKey, serialize($dto));

        sleep(5);
    }
}
