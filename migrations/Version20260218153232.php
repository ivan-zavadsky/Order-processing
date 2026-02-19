<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260218153232 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // Создание таблицы user
        $userTable = $schema->createTable('user');
        $userTable->addColumn(
            'id',
            'integer',
            [
                'autoincrement' => true,
                'notnull' => true
            ]
        );
        $userTable->addColumn(
            'name',
            'string',
            [
                'length' => 255,
                'notnull' => true
            ]
        );
        $userTable->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        // Удаление таблицы user
        $schema->dropTable('user');
    }
}
