<?php

namespace SimpleBank\Infrastructure\Persistance\BankBranch;

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
    }

    public function search(BankBranchId $bankBranchId): ?array
    {
        $bankBranches = array();

        /**
         * @var BankBranch $bankBranch
         */
        foreach ($this->bankBranches as $bankBranch) {
            if ($bankBranch->id()->equals($bankBranchId)) {
                $bankBranches[] = $bankBranch;
            }
        }

        return $bankBranches;
    }

    public function exists(BankBranch $bankBranch): bool
    {
        foreach ($this->bankBranches as $bankBranch) {
            if ($bankBranch->name() == $bankBranch->) {
                $bankBranches[] = $bankBranch;
            }
        }

        return $bankBranches;
    }

    public function all(): ?array
    {
        // TODO: Implement all() method.
    }
}
