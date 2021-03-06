<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210427140229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create_bank_branches_table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE bank_branches (
               id VARCHAR(255) NOT NULL, 
               name VARCHAR(50) NOT NULL,
               location  VARCHAR(100) NOT NULL,
               PRIMARY KEY(id),
               CONSTRAINT uq_name_location UNIQUE (name,location) 
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE bank_branches');
    }
}
