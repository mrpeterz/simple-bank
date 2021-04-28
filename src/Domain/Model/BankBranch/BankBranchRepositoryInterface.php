<?php

namespace SimpleBank\Domain\Model\BankBranch;

interface BankBranchRepositoryInterface
{
    public function save(BankBranch $bankBranch): bool;

    public function search(BankBranchId $bankBranchId): ?BankBranch;
}
