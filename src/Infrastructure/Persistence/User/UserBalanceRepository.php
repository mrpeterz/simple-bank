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
