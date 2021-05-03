<?php

namespace SimpleBank\Application\DataTransformer\BankBranch;

use SimpleBank\Application\DataTransformer\Dto;

class BankBranchDto implements Dto
{
    private ?string $name = null;
    private ?string $location = null;

    public function name(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function location(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): void
    {
        $this->location = $location;
    }
}
