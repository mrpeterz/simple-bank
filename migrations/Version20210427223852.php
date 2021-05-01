<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210427223852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create_users_table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE users (
               id VARCHAR(255) NOT NULL,
               name VARCHAR(50) NOT NULL,
               bank_branch_id VARCHAR(255),
               PRIMARY KEY(id),
               INDEX ix_users_bank_branch_id(bank_branch_id),
               CONSTRAINT fk_users_bank_branches_bank_branch_id FOREIGN KEY (bank_branch_id)
                   REFERENCES bank_branches(id)
                   ON DELETE CASCADE
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
    }
}
