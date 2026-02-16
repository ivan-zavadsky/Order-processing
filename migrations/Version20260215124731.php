<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260215124731 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->connection->insert('product', [
            'name' => 'iPhone 17',
            'price' => 200000,
            'sku' => 'i17',
        ]);
    }

    public function down(Schema $schema): void
    {
        $this->connection->delete('product', [
            'sku' => 'i17',
        ]);
    }
}
