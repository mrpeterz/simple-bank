<?php

namespace SimpleBank\Infrastructure\User;

use Doctrine\DBAL\Connection;
use SimpleBank\Domain\Model\BankBranch\BankBranchId;
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

    public function searchByMaxBalance(UserId $userId, BankBranchId $bankBranchId): ?array
    {
        // TODO: Implement searchByMaxBalance() method.
    }

    public function searchByUserId(UserId $userId): ?array
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
