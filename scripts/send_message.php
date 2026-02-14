<?php
// scripts/send_message.php
use App\Message\OrderCreatedMessage;
use Symfony\Component\Messenger\MessageBusInterface;

require __DIR__ . '/../vendor/autoload.php';

$container = require __DIR__ . '/../config/bootstrap.php';

/** @var MessageBusInterface $bus */
$bus = $container->get(MessageBusInterface::class);

$bus->dispatch(new OrderCreatedMessage(1, 1));

echo "Message dispatched\n";
