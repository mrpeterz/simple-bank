<?php

namespace SimpleBank\Infrastructure\User;

use Doctrine\DBAL\Connection;
use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserId;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): bool
    {
        $stm = $this->connection->prepare("INSERT INTO users VALUES(?,?,?)");
        $stm->bindValue(1, $user->id());
        $stm->bindValue(2, $user->name());
        $stm->bindValue(3, $user->bankBranchId());
        return $stm->execute();
    }

    public function search(UserId $userId): ?array
    {
        $stm = $this->connection->prepare(
<<<SQL
        SELECT 
               u.id AS user_id, 
               u.name AS user_name, 
               ub.balance AS user_balance,
               b.id AS bank_branch_id, 
               b.name AS bank_branch_name, 
               b.location AS bank_branch_location 
        FROM users u 
        JOIN bank_branches b ON u.bank_branch_id = b.id
        JOIN user_balances ub ON b.id = ub.bank_branch_id AND u.id = ub.user_id
        WHERE u.id = ?
SQL
        );

        $stm->bindValue(1, $userId);
        $rst = $stm->executeQuery();
        return $rst->fetchAssociative();
    }

    public function all(): ?array
    {
        $stm = $this->connection->prepare(
<<<SQL
        SELECT 
               u.id AS user_id, 
               u.name AS user_name, 
               ub.balance AS user_balance,
               b.id AS bank_branch_id, 
               b.name AS bank_branch_name, 
               b.location AS bank_branch_location 
        FROM users u 
        JOIN bank_branches b ON u.bank_branch_id = b.id
        JOIN user_balances ub ON b.id = ub.bank_branch_id AND u.id = ub.user_id
        ORDER BY b.id, ub.balance DESC
SQL
        );
        $rst = $stm->executeQuery();
        return $rst->fetchAllAssociative();
    }

    public function nextIdentity(): UserId
    {
        return new UserId();
    }
}
