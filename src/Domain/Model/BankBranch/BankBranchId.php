<?php

namespace SimpleBank\Domain\Model\BankBranch;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class BankBranchId
{
    private UuidInterface $branchId;

    public function __construct(string $branchId)
    {
        $branchId = Uuid::fromString($branchId);
        $this->branchId = $branchId;
    }

    public function toString(): string
    {
        return $this->branchId->toString();
    }
}
