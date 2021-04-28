<?php


namespace SimpleBank\Infrastructure\BankBranch;

use SimpleBank\Domain\Model\BankBranch\BankBranch;
use SimpleBank\Domain\Model\BankBranch\BankBranchId;
use SimpleBank\Domain\Model\BankBranch\BankBranchRepositoryInterface;

class BankBranchRepository implements BankBranchRepositoryInterface
{
    public function save(BankBranch $bankBranch): bool
    {
        // TODO: Implement save() method.
    }

    public function search(BankBranchId $bankBranchId): ?BankBranch
    {
        // TODO: Implement search() method.
    }
}
