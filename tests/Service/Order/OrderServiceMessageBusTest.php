<?php

namespace App\Tests\Service\Order;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Enum\OrderStatus;
use App\Message\OrderCreatedMessage;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\Order\OrderDto;
use App\Service\Order\OrderItemDto;
use App\Service\Order\OrderService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class OrderServiceMessageBusTest extends TestCase
{
    private OrderRepository $orderRepository;
    private ProductRepository $productRepository;
    private MessageBusInterface $messageBus;
    private OrderService $orderService;

    protected function setUp(): void
    {
        // Создаем mock-объекты для зависимостей
        $this->orderRepository = $this->createMock(OrderRepository::class);
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);

        // Создаем экземпляр OrderService с mock-объектами
        $this->orderService = new OrderService(
            $this->orderRepository,
            $this->productRepository,
            $this->messageBus
        );
    }

    public function testMessageBusDispatchesOrderCreatedMessage(): void
    {
        // Создаем тестовый продукт
        $product = new Product();
        $product->setName('Test Product');
        $product->setPrice('19.99');
        $product->setSku('TEST-001');

        // Настраиваем поведение mock-объекта productRepository
        $this->productRepository
            ->expects($this->once())
            ->method('findOneByName')
            ->with('Test Product')
            ->willReturn($product);

        // Настраиваем поведение mock-объекта orderRepository
        $this->orderRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Order $order) {
                // Проверяем, что заказ создан с правильными данными
                return $order->getUserId() === 123
                    && $order->getStatus() === OrderStatus::NEW
                    && $order->getItems()->count() === 1;
            }));

        // Настраиваем поведение mock-объекта messageBus
        $this->messageBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function (OrderCreatedMessage $message) {
                // Проверяем, что сообщение содержит правильные данные
                return $message->userId === 123;
            }))
            ->willReturn(new Envelope(new OrderCreatedMessage(1, 123)));

        // Создаем DTO для заказа
        $orderItemDto = new OrderItemDto();
        $orderItemDto->product = 'Test Product';
        $orderItemDto->quantity = 2;

        $orderDto = new OrderDto(
            userId: 123,
            items: [$orderItemDto]
        );

        // Вызываем метод создания заказа
        $result = $this->orderService->create($orderDto);

        // Проверяем результат
        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals(123, $result->getUserId());
        $this->assertEquals(OrderStatus::NEW, $result->getStatus());
        $this->assertCount(1, $result->getItems());

        // Проверяем элементы заказа
        $items = $result->getItems();
        $this->assertContainsOnlyInstancesOf(OrderItem::class, $items);

        $orderItem = $items->first();
        $this->assertEquals($product, $orderItem->getProduct());
        $this->assertEquals(2, $orderItem->getQuantity());
        $this->assertEquals('19.99', $orderItem->getPrice());
    }

    public function testMessageBusDispatchesWithMultipleItems(): void
    {
        // Создаем тестовые продукты
        $product1 = new Product();
        $product1->setName('Product 1');
        $product1->setPrice('10.00');
        $product1->setSku('PROD-001');

        $product2 = new Product();
        $product2->setName('Product 2');
        $product2->setPrice('20.00');
        $product2->setSku('PROD-002');

        // Настраиваем поведение mock-объекта productRepository
        $this->productRepository
            ->expects($this->exactly(2))
            ->method('findOneByName')
            ->willReturnCallback(function ($name) use ($product1, $product2) {
                if ($name === 'Product 1') {
                    return $product1;
                }
                if ($name === 'Product 2') {
                    return $product2;
                }
                return null;
            });

        // Настраиваем поведение mock-объекта orderRepository
        $this->orderRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Order $order) {
                // Проверяем, что заказ создан с правильными данными
                return $order->getUserId() === 456
                    && $order->getStatus() === OrderStatus::NEW
                    && $order->getItems()->count() === 2;
            }));

        // Настраиваем поведение mock-объекта messageBus
        $this->messageBus
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->callback(function (OrderCreatedMessage $message) {
                // Проверяем, что сообщение содержит правильные данные
                return $message->userId === 456;
            }))
            ->willReturn(new Envelope(new OrderCreatedMessage(1, 456)));

        // Создаем DTO для заказа с несколькими товарами
        $orderItemDto1 = new OrderItemDto();
        $orderItemDto1->product = 'Product 1';
        $orderItemDto1->quantity = 1;

        $orderItemDto2 = new OrderItemDto();
        $orderItemDto2->product = 'Product 2';
        $orderItemDto2->quantity = 3;

        $orderDto = new OrderDto(
            userId: 456,
            items: [$orderItemDto1, $orderItemDto2]
        );

        // Вызываем метод создания заказа
        $result = $this->orderService->create($orderDto);

        // Проверяем результат
        $this->assertInstanceOf(Order::class, $result);
        $this->assertEquals(456, $result->getUserId());
        $this->assertEquals(OrderStatus::NEW, $result->getStatus());
        $this->assertCount(2, $result->getItems());

        // Проверяем элементы заказа
        $items = $result->getItems()->toArray();

        $this->assertEquals('Product 1', $items[0]->getProduct()->getName());
        $this->assertEquals(1, $items[0]->getQuantity());
        $this->assertEquals('10.00', $items[0]->getPrice());

        $this->assertEquals('Product 2', $items[1]->getProduct()->getName());
        $this->assertEquals(3, $items[1]->getQuantity());
        $this->assertEquals('20.00', $items[1]->getPrice());
    }
}
