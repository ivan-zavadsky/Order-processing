<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260210102123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Create order table
        $orderTable = $schema->createTable('order');
        $orderTable->addColumn('id', 'integer', ['autoincrement' => true, 'notnull' => true]);
        $orderTable->addColumn('user_id', 'integer', ['notnull' => false]);
        $orderTable->setPrimaryKey(['id']);

        // Add my_order_id column to product table
        $productTable = $schema->getTable('product');
        $productTable->addColumn('my_order_id', 'integer', ['notnull' => false]);

        // Add foreign key constraint
        $productTable->addForeignKeyConstraint(
            'order',
            ['my_order_id'],
            ['id'],
            ['onDelete' => 'SET NULL'],
            'FK_D34A04ADBFCDF877'
        );

        // Add index
        $productTable->addIndex(['my_order_id'], 'IDX_D34A04ADBFCDF877');
    }

    public function down(Schema $schema): void
    {
        // Drop order table
        $schema->dropTable('order');

        // Drop foreign key constraint, index and column from product table
        $productTable = $schema->getTable('product');
        $productTable->removeForeignKey('FK_D34A04ADBFCDF877');
        $productTable->dropIndex('IDX_D34A04ADBFCDF877');
        $productTable->dropColumn('my_order_id');
    }
}
