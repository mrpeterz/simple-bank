<?php

namespace SimpleBank\Application\Service\BankBranch;

use SimpleBank\Application\DataTransformer\BankBranch\BankBranchDto;
use SimpleBank\Domain\Model\BankBranch\BankBranch;
use SimpleBank\Domain\Model\BankBranch\BankBranchAlreadyExistsException;
use SimpleBank\Domain\Model\BankBranch\BankBranchNotExistsException;
use SimpleBank\Domain\Model\BankBranch\BankBranchRepositoryInterface;
use SimpleBank\Domain\Transactions;

class CreateBankBranchService
{
    private BankBranchRepositoryInterface $bankBranchRepository;
    private Transactions $transactionalManager;

    public function __construct(
        BankBranchRepositoryInterface $bankBranchRepository,
        Transactions $transactionalManager
    ) {
        $this->bankBranchRepository = $bankBranchRepository;
        $this->transactionalManager = $transactionalManager;
    }

    public function save(BankBranchDto $bankBranchDto): bool
    {
        $this->transactionalManager->beginTransaction();

        try{
            $bankBranch = new BankBranch(
                $this->bankBranchRepository->nextIdentity(),
                $bankBranchDto->name(),
                $bankBranchDto->location()
            );

            if (!$bankBranch) {
                throw new BankBranchNotExistsException('Bank branch cannot be null.');
            }

            if ($this->bankBranchRepository->exists($bankBranch)) {
                throw new BankBranchAlreadyExistsException('Bank branch already exists.');
            }

            $this->bankBranchRepository->save($bankBranch);

            return $this->transactionalManager->commit();

        } catch (\Exception $exception) {
            $this->transactionalManager->rollBack();
            throw $exception;
        }
    }
}
