<?php

namespace SimpleBank\Infrastructure\User;

use Doctrine\DBAL\Connection;
use SimpleBank\Domain\Model\User\UserBalance;
use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;
use SimpleBank\Domain\Model\User\UserId;

class UserBalanceRepository implements UserBalanceRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(UserBalance $userBalance): bool
    {
        $stm = $this->connection->prepare("INSERT INTO user_balances VALUES(?,?,?)");
        $stm->bindValue(1, $userBalance->userId());
        $stm->bindValue(2, $userBalance->bankBranchId());
        $stm->bindValue(3, $userBalance->balance());
        return $stm->execute();
    }

    public function searchByHightestBalance(): ?array
    {
        $stm = $this->connection->prepare(
            <<<SQL
        SELECT 
           bb.name AS bank_branch_name,
           bb.location AS bank_branch_location,
           ifnull(max(ub.balance),0) AS bank_branch_hightest
        FROM bank_branches bb
        LEFT JOIN user_balances ub ON bb.id = ub.bank_branch_id
        LEFT JOIN users u ON bb.id = u.bank_branch_id AND ub.user_id = u.id
        GROUP BY bb.id;
SQL
        );
        $rst = $stm->executeQuery();
        return $rst->fetchAllAssociative();
    }

    public function searchByTopBankBranches(): ?array
    {
        // TODO: Implement searchByUserId() method.
    }

    public function updateBalance(UserId $userId, float $balance): ?bool
    {
        $stm = $this->connection->prepare("UPDATE user_balances SET balance = ? WHERE user_id = ?");
        $stm->bindValue(1, $balance);
        $stm->bindValue(2, $userId->id());
        return $stm->execute();
    }
}
