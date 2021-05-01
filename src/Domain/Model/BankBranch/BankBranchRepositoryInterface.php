<?php

namespace SimpleBank\Domain\Model\BankBranch;

interface BankBranchRepositoryInterface
{
    public function save(BankBranch $bankBranch): bool;

    public function search(BankBranchId $bankBranchId): ?array;

    public function all(): ?array;
}
