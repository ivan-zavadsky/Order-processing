<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260213064739 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
//        $this->addSql("CREATE TYPE order_status AS ENUM ('new','processing','failed')");
//        $this->addSql('ALTER TABLE "order" ADD COLUMN status order_status NOT NULL DEFAULT \'new\'');
        $orderTable = $schema->getTable('order');

        if (!$orderTable->hasColumn('status')) {
            $orderTable->addColumn('status', 'string', [
                'notnull' => true,
                'default' => 'new',
                'length' => 20
            ]);
        }

    }

    public function down(Schema $schema): void
    {
        $orderTable = $schema->getTable('order');

        if ($orderTable->hasColumn('status')) {
            $orderTable->dropColumn('status');
        }
    }
}
