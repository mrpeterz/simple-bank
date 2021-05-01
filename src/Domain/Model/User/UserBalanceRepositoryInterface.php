<?php

namespace SimpleBank\Domain\Model\User;

interface UserBalanceRepositoryInterface
{
    public function save(UserBalance $userBalance): bool;

    public function updateBalance(UserId $userId, float $balance): ?bool;

    public function searchByHightestBalance(): ?array;

    public function searchByTopBankBranches(): ?array;
}
