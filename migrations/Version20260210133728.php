<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260210133728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $productTable = $schema->getTable('product');
        if ($productTable->hasColumn('my_order_id')) {
            $productTable->dropColumn('my_order_id');
        }
    }

    public function down(Schema $schema): void
    {
        $productTable = $schema->getTable('product');

        if (!$productTable->hasColumn('my_order_id')) {
            $productTable->addColumn('my_order_id', 'integer', ['notnull' => false]);
        }

        if (!$productTable->hasIndex('idx_d34a04adbfcdf877')) {
            $productTable->addIndex(['my_order_id'], 'idx_d34a04adbfcdf877');
        }

        if (!$productTable->hasForeignKey('fk_d34a04adbfcdf877')) {
            $productTable->addForeignKeyConstraint(
                'order',
                ['my_order_id'],
                ['id'],
                ['name' => 'fk_d34a04adbfcdf877']
            );
        }
    }
}
