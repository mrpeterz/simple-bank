<?php

namespace SimpleBank\Domain\Model\User;

interface UserRepositoryInterface
{
    public function nextIdentity(): UserId;

    public function save(User $user): bool;

    public function search(UserId $userId): ?array;

    public function all(): ?array;

    public function allOthers(UserId $userId): ?array;
}
