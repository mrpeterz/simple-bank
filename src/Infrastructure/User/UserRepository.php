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
        $stm = $this->connection->prepare("SELECT * FROM users WHERE id = ?");
        $stm->bindValue(1, $userId);
        return $stm->executeQuery()->fetchAssociative();
    }

    public function all(): ?array
    {
        $stm = $this->connection->prepare("SELECT * FROM users WHERE");
        return $stm->executeQuery()->fetchAllAssociative();
    }

    public function nextIdentity(): UserId
    {
        return new UserId();
    }
}
