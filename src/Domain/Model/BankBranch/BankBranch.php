<?php

namespace SimpleBank\Domain\Model\BankBranch;

class BankBranch
{
    private BankBranchId $id;
    private string $name;
    private string $location;

    public function __construct(
      BankBranchId $id,
      string $name,
      string $location
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->location = $location;
    }

    public function id(): BankBranchId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function location(): string
    {
        return $this->location;
    }

    public function toArray(): array
    {
        return
            [
                'id' => (string)$this->id(),
                'name' => (string)$this->name(),
                'location' => (string)$this->location()
            ];
    }
}
