<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260210073919 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // product table
        $product = $schema->createTable('product');
        $product->addColumn('id', 'integer', [
            'autoincrement' => true,
        ]);
        $product->addColumn('name', 'string', [
            'length' => 255,
        ]);
        $product->addColumn('price', 'decimal', [
            'precision' => 10,
            'scale' => 2,
        ]);
        $product->addColumn('sku', 'string', [
            'length' => 255,
        ]);
        $product->setPrimaryKey(['id']);

        // messenger_messages table
        $messenger = $schema->createTable('messenger_messages');
        $messenger->addColumn('id', 'bigint', [
            'autoincrement' => true,
        ]);
        $messenger->addColumn('body', 'text');
        $messenger->addColumn('headers', 'text');
        $messenger->addColumn('queue_name', 'string', [
            'length' => 190,
        ]);
        $messenger->addColumn('created_at', 'datetime');
        $messenger->addColumn('available_at', 'datetime');
        $messenger->addColumn('delivered_at', 'datetime', [
            'notnull' => false,
        ]);
        $messenger->setPrimaryKey(['id']);
        $messenger->addIndex(
            ['queue_name', 'available_at', 'delivered_at', 'id'],
            'IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750'
        );
    }
    public function down(Schema $schema): void
    {
        $schema->dropTable('messenger_messages');
        $schema->dropTable('product');
    }
}
