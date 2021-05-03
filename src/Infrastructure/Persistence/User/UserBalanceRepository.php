<?php

namespace SimpleBank\Infrastructure\Persistence\User;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Exception;
use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserBalance;
use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;

class UserBalanceRepository implements UserBalanceRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(UserBalance $userBalance): bool
    {
        try {

            $stm = $this->connection->prepare("INSERT INTO user_balances VALUES(?,?,?)");
            $stm->bindValue(1, $userBalance->userId());
            $stm->bindValue(2, $userBalance->bankBranchId());
            $stm->bindValue(3, $userBalance->balance());
            return $stm->executeStatement() > 0;

        }catch (Exception $exception) {
            throw $exception;
        }
    }

    public function searchByHighestBalance(): ?array
    {
        try {

            $stm = $this->connection->prepare(
                <<<SQL
            SELECT 
               bb.name AS bank_branch_name,
               bb.location AS bank_branch_location,
               ifnull(MAX(ub.balance),0) AS bank_branch_highest
            FROM bank_branches bb
            LEFT JOIN user_balances ub ON bb.id = ub.bank_branch_id
            LEFT JOIN users u ON bb.id = u.bank_branch_id AND ub.user_id = u.id
            GROUP BY bb.id;
SQL
            );

            $rst = $stm->executeQuery();
            return $rst->fetchAllAssociative();

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function searchByTopBankBranches(): ?array
    {
        try {

            $stm = $this->connection->prepare(
                    <<<SQL
        SELECT 
               bb.name AS bank_branch_name, 
               bb.location AS bank_branch_location
        FROM bank_branches bb
        JOIN user_balances ub ON bb.id = ub.bank_branch_id
        JOIN users u ON bb.id = u.bank_branch_id AND ub.user_id = u.id
        WHERE ub.balance > 50000
        GROUP BY bb.name, bb.location
        HAVING COUNT(*) > 2;
SQL
                );

            $rst = $stm->executeQuery();
            return $rst->fetchAllAssociative();

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateBalance(User $user): bool
    {
        try {

            $stm = $this->connection->prepare("UPDATE user_balances SET balance = ? WHERE user_id = ?");
            $stm->bindValue(1, $user->userBalance()->balance());
            $stm->bindValue(2, $user->id());
            return $stm->executeStatement() > 0;

        }catch (Exception $e) {
            throw $e;
        }
    }
}
