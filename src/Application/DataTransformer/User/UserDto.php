<?php

namespace SimpleBank\Application\DataTransformer\User;

use SimpleBank\Application\DataTransformer\Dto;

class UserDto implements Dto
{
    private string $name;
    private string $bankBranchId;
    private float $balance;

    public function name(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function bankBranchId(): string
    {
        return $this->bankBranchId;
    }

    public function setBankBranchId(string $bankBranchId): void
    {
        $this->bankBranchId = $bankBranchId;
    }

    public function balance(): float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }
}
