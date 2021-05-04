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

    public function setBalance(float $balance): void
    {
        $this->userBalance()->setBalance($balance);
    }

    public function userBalance(): UserBalance
    {
        return $this->userBalance;
    }

    public static function fromArray(array $user): User
    {
        return new self(
            new UserId($user['user_id']),
            $user['user_name'],
            new BankBranchId($user['bank_branch_id']),
            $user['user_balance']
        );
    }
}
