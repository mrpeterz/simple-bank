<?php

namespace SimpleBank\Infrastructure\Persistence\BankBranch;

use SimpleBank\Domain\Model\BankBranch\BankBranch;
use SimpleBank\Domain\Model\BankBranch\BankBranchId;
use SimpleBank\Domain\Model\BankBranch\BankBranchRepositoryInterface;

class InMemoryBankBranchRepository implements BankBranchRepositoryInterface
{
    /**
     * @var BankBranch[]
     */
    private array $bankBranches = array();

    public function nextIdentity(): BankBranchId
    {
        return new BankBranchId();
    }

    public function save(BankBranch $bankBranch): bool
    {
        $this->bankBranches[$bankBranch->id()->id()] = $bankBranch;
        return array_key_exists($bankBranch->id()->id(), $this->bankBranches);
    }

    public function search(BankBranchId $bankBranchId): ?array
    {
        $bankBranch = array();

        foreach ($this->bankBranches as $bankBranch) {
            if ($bankBranch->id()->equals($bankBranchId)) {
                return $bankBranch = [
                    'bank_branch_id' => $bankBranch->id(),
                    'bank_branch_name' => $bankBranch->name(),
                    'bank_branch_location' => $bankBranch->location()
                ];
            }
        }

        return $bankBranch;
    }

    public function exists(BankBranch $bankBranch): bool
    {
        foreach ($this->bankBranches as $item) {
            if ($item->name() == $bankBranch->name() &&
                $item->location() == $bankBranch->location()
            ) {
                return true;
            }
        }

        return false;
    }

    public function all(): ?array
    {
        return $this->bankBranches;
    }
}
