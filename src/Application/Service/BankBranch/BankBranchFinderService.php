<?php

namespace SimpleBank\Application\Service\BankBranch;

use SimpleBank\Domain\Model\BankBranch\BankBranchRepositoryInterface;

class BankBranchFinderService
{
    private BankBranchRepositoryInterface $bankBranchRepository;

    public function __construct(BankBranchRepositoryInterface $bankBranchRepository)
    {
        $this->bankBranchRepository = $bankBranchRepository;
    }

    public function listBankBranches(): ?array
    {
        return $this->bankBranchRepository->all();
    }
}
