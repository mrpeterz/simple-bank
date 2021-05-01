<?php

namespace SimpleBank\Domain\Model\User;

use SimpleBank\Domain\Model\BankBranch\BankBranchId;
use SimpleBank\Domain\Model\User\AggregateRoot\AggregateRoot;

class User extends AggregateRoot
{
    private UserId $id;
    private string $name;
    private BankBranchId $bankBranchId;
    private UserBalance $userBalance;

    public function __construct(
      UserId $id,
      string $name,
      BankBranchId $bankBranchId,
      float $balance
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->bankBranchId = $bankBranchId;
        $this->userBalance = new UserBalance($id,$bankBranchId,$balance);
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

    public function updateBalance(UserBalance $userBalance): void
    {
        $this->userBalance = $userBalance;
    }

    public function userBalance(): UserBalance
    {
        return $this->userBalance;
    }

    public function toArray(): array
    {
        return
            [
                'id' => (string)$this->id(),
                'name' => (string)$this->name(),
                'branch_id' => (string)$this->bankBranchId()
            ];
    }
}
