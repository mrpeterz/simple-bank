<?php

namespace SimpleBank\Domain\Model\User;

interface UserBalanceRepositoryInterface
{
    public function save(UserBalance $userBalance): bool;

    public function updateBalance(User $user): bool;
}
