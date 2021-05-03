<?php

namespace SimpleBank\Infrastructure\Persistence\User;

use SimpleBank\Domain\Model\User\User;
use SimpleBank\Domain\Model\User\UserBalance;
use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;

class InMemoryUserBalanceRepository implements UserBalanceRepositoryInterface
{
    /**
     * @var UserBalance[]
     */
    private array $userBalances = array();

    public function save(UserBalance $userBalance): bool
    {
        $this->userBalances[] = $userBalance;
        return true;
    }

    public function updateBalance(User $user): bool
    {
        foreach ($this->userBalances as $key => $userBalance) {
            if ($userBalance->userId() === $user->id()
            && $userBalance->bankBranchId() === $user->bankBranchId()) {
                $this->userBalances[$key]->setBalance($user->userBalance()->balance());
                return true;
            }
        }

        return false;
    }
}
