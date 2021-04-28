<?php

namespace SimpleBank\Domain\Model\BankBranch;

use Ramsey\Uuid\Uuid;

class BankBranchId
{
    private string $id;

    public function __construct(string $id = null)
    {
        $this->id = null === $id
            ? Uuid::uuid4()->toString() :
            $id;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function equals(BankBranchId $bankBranchId): bool
    {
        return $this->id() === $bankBranchId->id();
    }

    public function __toString(): string
    {
        return $this->id();
    }
}
