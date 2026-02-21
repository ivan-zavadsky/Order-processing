<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260220072239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Clean up orders with invalid user_id and add foreign key constraint';
    }

    public function up(Schema $schema): void
    {
        $platform = $this->connection->getDatabasePlatform();

        // Экранируем имена таблиц для переносимости
        $orderTable = $platform->quoteIdentifier('order');
        $userTable = $platform->quoteIdentifier('user');
        $orderItemTable = $platform->quoteIdentifier('order_item');

        // 1️⃣ Удаляем order_item для заказов, которые будут удалены
        $this->addSql("
            DELETE FROM $orderItemTable
            WHERE order_id IN (
                SELECT id FROM $orderTable
                WHERE user_id IS NOT NULL
                AND NOT EXISTS (
                    SELECT 1 FROM $userTable u WHERE u.id = $orderTable.user_id
                )
            )
        ");

        // 2️⃣ Удаляем сами заказы с несуществующим user_id
        $this->addSql("
            DELETE FROM $orderTable
            WHERE user_id IS NOT NULL
            AND NOT EXISTS (
                SELECT 1 FROM $userTable u WHERE u.id = $orderTable.user_id
            )
        ");

        // 3️⃣ Добавляем индекс на user_id
        $order = $schema->getTable('order');
        if (!$order->hasIndex('IDX_F5299398A76ED395')) {
            $order->addIndex(['user_id'], 'IDX_F5299398A76ED395');
        }

        // 4️⃣ Добавляем внешний ключ
        if (!$order->hasForeignKey('FK_F5299398A76ED395')) {
            $order->addForeignKeyConstraint(
                'user',
                ['user_id'],
                ['id'],
                ['onDelete' => 'CASCADE'],
                'FK_F5299398A76ED395'
            );
        }
    }

    public function down(Schema $schema): void
    {
        $order = $schema->getTable('order');

        // Удаляем внешний ключ
        if ($order->hasForeignKey('FK_F5299398A76ED395')) {
            $order->removeForeignKey('FK_F5299398A76ED395');
        }

        // Удаляем индекс
        if ($order->hasIndex('IDX_F5299398A76ED395')) {
            $order->dropIndex('IDX_F5299398A76ED395');
        }
    }
}
