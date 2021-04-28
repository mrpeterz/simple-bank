<?php

namespace SimpleBank\Domain\Model\User;

use SimpleBank\Domain\Model\BankBranch\BankBranchId;

class User
{
    private UserId $id;
    private string $name;
    private BankBranchId $branchId;

    public function __construct(
      UserId $id,
      string $name,
      BankBranchId $branchId
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->branchId = $branchId;
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function branchId(): BankBranchId
    {
        return $this->branchId;
    }
}
