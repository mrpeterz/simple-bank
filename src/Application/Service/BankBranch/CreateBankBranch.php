<?php

namespace SimpleBank\Application\Service\BankBranch;

use SimpleBank\Application\DataTransformer\BankBranch\BankBranchDto;
use SimpleBank\Domain\Model\BankBranch\BankBranch;
use SimpleBank\Domain\Model\BankBranch\BankBranchRepositoryInterface;

class CreateBankBranch
{
    private BankBranchRepositoryInterface $bankBranchRepository;

    public function __construct(BankBranchRepositoryInterface $bankBranchRepository)
    {
        $this->bankBranchRepository = $bankBranchRepository;
    }

    public function save(BankBranchDto $bankBranchDto): bool
    {
        $bankBranch = new BankBranch(
            $this->bankBranchRepository->nextIdentity(),
            $bankBranchDto->name(),
            $bankBranchDto->location()
        );

        return $this->bankBranchRepository->save($bankBranch);
    }
}
