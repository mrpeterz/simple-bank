<?php

namespace SimpleBank\Domain\Model\User;

use SimpleBank\Domain\Model\BankBranch\BankBranchId;

interface UserBalanceRepositoryInterface
{
    public function save(UserBalance $userBalance): bool;

    public function searchByMaxBalance(UserId $userId, BankBranchId $bankBranchId): ?array;

    public function searchByUserId(UserId $userId): ?array;

    public function updateBalance(UserId $userId, float $balance): ?bool;
}
