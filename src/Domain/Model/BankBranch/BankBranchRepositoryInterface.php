<?php

namespace SimpleBank\Domain\Model\BankBranch;

interface BankBranchRepositoryInterface
{
    public function nextIdentity(): BankBranchId;

    public function save(BankBranch $bankBranch): bool;

    public function search(BankBranchId $bankBranchId): ?array;

    public function exists(BankBranch $bankBranch): bool;

    public function all(): ?array;
}
