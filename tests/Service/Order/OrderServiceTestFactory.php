<?php

namespace App\Tests\Service\Order;

use App\Message\OrderCreatedMessage;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Service\Order\OrderService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class OrderServiceTestFactory
{
    private ?MockObject $orderRepositoryMock = null;
    private ?MockObject $productRepositoryMock = null;
    private ?MockObject $messageBusMock = null;

    public function __construct(private TestCase $testCase)
    {
    }

    public function createOrderService(): OrderService
    {
        /** @phpstan-ignore-next-line */
        return new OrderService(
            $this->getOrderRepositoryMock(),
            $this->getProductRepositoryMock(),
            $this->getMessageBusMock(),
        );
    }

    /**
     * @return OrderRepository&MockObject
     */
    public function createOrderRepositoryMock(): MockObject
    {
        if ($this->orderRepositoryMock === null) {
            $mock = $this->invokeCreateMock(OrderRepository::class);
            $mock->method('save')->willReturn(1);
            $this->orderRepositoryMock = $mock;
        }
        return $this->orderRepositoryMock;
    }

    /**
     * @return ProductRepository&MockObject
     */
    public function createProductRepositoryMock(): MockObject
    {
        if ($this->productRepositoryMock === null) {
            $this->productRepositoryMock = $this->invokeCreateMock(ProductRepository::class);
        }
        return $this->productRepositoryMock;
    }

    /**
     * @return MessageBusInterface&MockObject
     */
    public function createMessageBusMock(): MockObject
    {
        if ($this->messageBusMock === null) {
            $mock = $this->invokeCreateMock(MessageBusInterface::class);
            $mock->method('dispatch')
                ->willReturn(new Envelope(new OrderCreatedMessage(1, 123)));
            $this->messageBusMock = $mock;
        }
        return $this->messageBusMock;
    }

    private function getOrderRepositoryMock(): MockObject
    {
        return $this->orderRepositoryMock ?? $this->createOrderRepositoryMock();
    }

    private function getProductRepositoryMock(): MockObject
    {
        return $this->productRepositoryMock ?? $this->createProductRepositoryMock();
    }

    private function getMessageBusMock(): MockObject
    {
        return $this->messageBusMock ?? $this->createMessageBusMock();
    }

    private function invokeCreateMock(string $className): MockObject
    {
        // @phpstan-ignore-next-line
        $reflectionMethod = new ReflectionMethod(TestCase::class, 'createMock');
        // @phpstan-ignore-next-line
        $reflectionMethod->setAccessible(true);
        /** @var MockObject $mock */
        // @phpstan-ignore-next-line
        $mock = $reflectionMethod->invoke($this->testCase, $className);
        return $mock;
    }
}



