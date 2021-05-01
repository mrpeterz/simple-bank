<?php

namespace SimpleBank\Application\Service\Stats;

use SimpleBank\Domain\Model\User\UserBalanceRepositoryInterface;

class StatsFactory
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
}
