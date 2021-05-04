<?php

declare(strict_types=1);

namespace SimpleBank\Domain\Model\BankBranch;

interface StatsRepositoryInterface
{
    public function searchByHighestBalance(): ?array;

    public function searchByTopBankBranches(): ?array;
}
