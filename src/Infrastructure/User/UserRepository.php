<?php

namespace SimpleBank\Infrastructure\User;

use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserId;
use SimpleBank\Domain\Model\User\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function save(User $user): bool
    {

    }

    public function search(UserId $userId): ?User
    {

    }
}
