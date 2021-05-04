<?php

declare(strict_types=1);

namespace SimpleBank\Application\Service\BankBranch;

use SimpleBank\Domain\Model\BankBranch\StatsRepositoryInterface;

class BankBranchStatsService
{
    private StatsRepositoryInterface $statsRepository;

    public function __construct(StatsRepositoryInterface $statsRepository)
    {
        $this->statsRepository = $statsRepository;
    }

    public function highestBalances(): ?array
    {
        return $this->statsRepository->searchByHighestBalance();
    }

    public function topBankBranches(): ?array
    {
        return $this->statsRepository->searchByTopBankBranches();
    }
}
