<?php

namespace SimpleBank\Domain\Model\User;

use SimpleBank\Domain\Model\BankBranch\BankBranchId;

class UserBalance
{
    private UserId $userId;
    private BankBranchId $bankBranchId;
    private float $balance;

    public function __construct(
        UserId $userId,
        BankBranchId $bankBranchId,
        float $balance
    ) {
        $this->userId = $userId;
        $this->bankBranchId = $bankBranchId;
        $this->balance = $balance;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function bankBranchId(): BankBranchId
    {
        return $this->bankBranchId;
    }

    public function balance(): float
    {
        return $this->balance;
    }
}
