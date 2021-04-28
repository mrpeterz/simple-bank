<?php

namespace SimpleBank\Domain\Model\User;

use SimpleBank\Domain\Model\BankBranch\BankBranchId;

class User
{
    private UserId $id;
    private string $name;
    private BankBranchId $bankBranchId;

    public function __construct(
      UserId $id,
      string $name,
      BankBranchId $bankBranchId
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->bankBranchId = $bankBranchId;
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function bankBranchId(): BankBranchId
    {
        return $this->bankBranchId;
    }
}
