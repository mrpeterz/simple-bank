<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210501164228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create_user_balances';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            CREATE TABLE user_balances (
               user_id VARCHAR(255) NOT NULL,
               bank_branch_id VARCHAR(255) NOT NULL,
               balance DOUBLE NOT NULL DEFAULT 0,
               PRIMARY KEY(user_id,bank_branch_id),
               INDEX ix_user_balances_bank_branch_id(bank_branch_id),
               INDEX ix_user_balances_user_id(user_id),
               CONSTRAINT fk_user_balances_users_user_id FOREIGN KEY (user_id)
                   REFERENCES users(id)
                   ON DELETE CASCADE,
               CONSTRAINT fk_user_balances_bank_branches_branch_id FOREIGN KEY (bank_branch_id)
                   REFERENCES bank_branches(id)                   
                   ON DELETE CASCADE
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;        ');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user_balances');
    }
}
