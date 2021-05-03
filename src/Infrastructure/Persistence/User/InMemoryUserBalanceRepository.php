<?php


namespace SimpleBank\Infrastructure\Persistence\User;


use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserBalance;
use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;

class InMemoryUserBalanceRepository implements UserBalanceRepositoryInterface
{

    public function save(UserBalance $userBalance): bool
    {
        // TODO: Implement save() method.
    }

    public function updateBalance(User $user): ?bool
    {
        // TODO: Implement updateBalance() method.
    }

    public function searchByHighestBalance(): ?array
    {
        // TODO: Implement searchByHighestBalance() method.
    }

    public function searchByTopBankBranches(): ?array
    {
        // TODO: Implement searchByTopBankBranches() method.
    }
}
