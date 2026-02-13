<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260211150813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $orderItem = $schema->getTable('order_item');

        // Удаляем старое ограничение внешнего ключа и индекс
        if ($orderItem->hasForeignKey('fk_52ea1f09dd842e46')) {
            $orderItem->removeForeignKey('fk_52ea1f09dd842e46');
        }

        if ($orderItem->hasIndex('idx_52ea1f09dd842e46')) {
            $orderItem->dropIndex('idx_52ea1f09dd842e46');
        }

        // Переименовываем колонку
        if ($orderItem->hasColumn('position_id')) {
            $orderItem->renameColumn('position_id', 'order_id');
        }

        // Создаем новый индекс и внешнее ограничение
        if (!$orderItem->hasIndex('IDX_52EA1F098D9F6D38')) {
            $orderItem->addIndex(['order_id'], 'IDX_52EA1F098D9F6D38');
        }

        if (!$orderItem->hasForeignKey('FK_52EA1F098D9F6D38')) {
            $orderItem->addForeignKeyConstraint(
                'order',
                ['order_id'],
                ['id'],
                ['name' => 'FK_52EA1F098D9F6D38']
            );
        }
    }

    public function down(Schema $schema): void
    {
        $orderItem = $schema->getTable('order_item');

        // Удаляем новое ограничение внешнего ключа и индекс
        if ($orderItem->hasForeignKey('FK_52EA1F098D9F6D38')) {
            $orderItem->removeForeignKey('FK_52EA1F098D9F6D38');
        }

        if ($orderItem->hasIndex('IDX_52EA1F098D9F6D38')) {
            $orderItem->dropIndex('IDX_52EA1F098D9F6D38');
        }

        // Возвращаем старое имя колонки
        if ($orderItem->hasColumn('order_id')) {
            $orderItem->renameColumn('order_id', 'position_id');
        }

        // Создаем старый индекс и внешнее ограничение
        if (!$orderItem->hasIndex('idx_52ea1f09dd842e46')) {
            $orderItem->addIndex(['position_id'], 'idx_52ea1f09dd842e46');
        }

        if (!$orderItem->hasForeignKey('fk_52ea1f09dd842e46')) {
            $orderItem->addForeignKeyConstraint(
                'order',
                ['position_id'],
                ['id'],
                ['name' => 'fk_52ea1f09dd842e46']
            );
        }
    }
}
