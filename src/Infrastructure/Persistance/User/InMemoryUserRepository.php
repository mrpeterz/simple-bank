<?php

namespace SimpleBank\Infrastructure\Persistance\User;

use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserId;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;

class InMemoryUserRepository implements UserRepositoryInterface
{

    public function nextIdentity(): UserId
    {
        // TODO: Implement nextIdentity() method.
    }

    public function save(User $user): bool
    {
        // TODO: Implement save() method.
    }

    public function search(UserId $userId): ?array
    {
        // TODO: Implement search() method.
    }

    public function all(): ?array
    {
        // TODO: Implement all() method.
    }

    public function allOthers(UserId $userId): ?array
    {
        // TODO: Implement allOthers() method.
    }
}
