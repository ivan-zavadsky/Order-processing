<?php

namespace App\Tests\Service\Order;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Enum\OrderStatus;
use App\Service\Order\OrderDto;
use App\Service\Order\OrderItemDto;
use App\Service\Order\OrderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

class OrderServiceTest extends TestCase
{
    private MockObject $orderRepository;
    private MockObject $productRepository;
    private OrderService $orderService;
    private OrderServiceTestFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new OrderServiceTestFactory($this);
        $this->orderRepository = $this->factory->createOrderRepositoryMock();
        $this->productRepository = $this->factory->createProductRepositoryMock();
        $this->orderService = $this->factory->createOrderService();
    }

    public function testCreateOrderSuccessfully(): void
    {
        // Создаем тестовый продукт из фиксту ры
        $product = $this->createProductFromFixture('testProduct');

        // Настраиваем поведение mock-объекта productRepository
        $this->productRepository
            ->method('findOneByName')
            ->with('Test Product')
            ->willReturn($product);

        // Настраиваем поведение mock-объекта orderRepository
        $this->orderRepository
            ->method('save')
            ->with($this->callback(function (Order $order) {
                // Проверяем, что заказ создан с правильными данными
                return
                    $order->getUserId() === 123
                    && $order->getStatus() === OrderStatus::NEW
                    && $order->getItems()->count() === 1;
            }))
            ->willReturn(1);

        // Создаем DTO для заказа
        $orderItemDto = new OrderItemDto();
        $orderItemDto->product = 'Test Product';
        $orderItemDto->quantity = 2;

        $orderDto = new OrderDto(
            userId: 123,
            items: [$orderItemDto]
        );

        // Вызываем метод создания заказа один раз и сохраняем результат
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

    private function createProductFromFixture(string $productKey): Product
    {
        $fixtureFile = __DIR__ . '/products.json';
        $fixtures = json_decode(file_get_contents($fixtureFile), true);

        if (!isset($fixtures[$productKey])) {
            throw new \RuntimeException("Product fixture '$productKey' not found in products.json");
        }

        $data = $fixtures[$productKey];
        $product = new Product();
        $product->setName($data['name']);
        $product->setPrice($data['price']);
        $product->setSku($data['sku']);

        return $product;
    }

    /**
     * @throws ExceptionInterface
     */
    public function testCreateOrderWithMultipleItems(): void
    {
        // Создаем тестовые продукты из фиксту ры
        $product1 = $this->createProductFromFixture('product1');
        $product2 = $this->createProductFromFixture('product2');

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
