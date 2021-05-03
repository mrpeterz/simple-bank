<?php

namespace SimpleBank\Infrastructure\Persistence\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Exception;
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
        try {

            $stm = $this->connection->prepare("INSERT INTO users VALUES(?,?,?)");
            $stm->bindValue(1, $user->id());
            $stm->bindValue(2, $user->name());
            $stm->bindValue(3, $user->bankBranchId());
            return $stm->execute();

        }catch (Exception $exception) {
            throw $exception;
        }
    }

    public function search(UserId $userId): ?array
    {
        try {

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

        }catch (Exception $exception) {
            throw $exception;
        }
    }

    public function all(): ?array
    {
        try {

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

        }catch (Exception $exception) {
            throw $exception;
        }
    }

    public function nextIdentity(): UserId
    {
        return new UserId();
    }

    public function allOthers(UserId $userId): ?array
    {
        try {

            $stm = $this->connection->prepare(
                <<<SQL
        SELECT 
               CONCAT(u.name, ' [' ,u.id, ']') AS user_name,           
               u.id AS user_id 
        FROM users u
        WHERE u.id <> ?
SQL
            );
            $stm->bindValue(1, $userId->id());
            $rst = $stm->executeQuery();
            return $rst->fetchAllKeyValue();

        }catch (Exception $exception) {
            throw $exception;
        }
    }
}
