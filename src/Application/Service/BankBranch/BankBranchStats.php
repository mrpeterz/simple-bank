<?php

namespace SimpleBank\Application\Service\BankBranch;

use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;

class BankBranchStats
{
    private UserBalanceRepositoryInterface $userBalanceRepository;

    public function __construct(UserBalanceRepositoryInterface $userBalanceRepository)
    {
        $this->userBalanceRepository = $userBalanceRepository;
    }

    public function highestBalances(): ?array
    {
        return $this->userBalanceRepository->searchByHighestBalance();
    }

    public function topBankBranches(): ?array
    {
        return $this->userBalanceRepository->searchByTopBankBranches();
    }
}
