<?php

namespace SimpleBank\Domain\Model\User;

interface UserRepositoryInterface
{
    public function save(User $user): bool;

    public function search(UserId $userId): ?array;
}
