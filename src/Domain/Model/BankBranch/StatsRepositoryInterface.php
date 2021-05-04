<?php

namespace SimpleBank\Domain\Model\BankBranch;

interface StatsRepositoryInterface
{
    public function searchByHighestBalance(): ?array;

    public function searchByTopBankBranches(): ?array;
}
