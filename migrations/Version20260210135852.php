<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260210135852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $orderItem = $schema->createTable('order_item');
        $orderItem->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $orderItem->addColumn('quantity', 'integer', ['notnull' => true]);
        $orderItem->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2, 'notnull' => true]);
        $orderItem->addColumn('position_id', 'integer', ['notnull' => true]);
        $orderItem->addColumn('product_id', 'integer', ['notnull' => true]);
        $orderItem->setPrimaryKey(['id']);
        $orderItem->addIndex(['position_id'], 'IDX_52EA1F09DD842E46');
        $orderItem->addIndex(['product_id'], 'IDX_52EA1F094584665A');
        $orderItem->addForeignKeyConstraint(
            'order',
            ['position_id'],
            ['id'],
            ['name' => 'FK_52EA1F09DD842E46']
        );
        $orderItem->addForeignKeyConstraint(
            'product',
            ['product_id'],
            ['id'],
            ['name' => 'FK_52EA1F094584665A']
        );
    }

    public function down(Schema $schema): void
    {
        if ($schema->hasTable('order_item')) {
            $schema->dropTable('order_item');
        }
    }
}
