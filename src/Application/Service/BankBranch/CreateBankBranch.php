<?php

namespace SimpleBank\Application\Service\BankBranch;

use SimpleBank\Application\DataTransformer\BankBranch\BankBranchDto;
use SimpleBank\Domain\Model\BankBranch\BankBranch;
use SimpleBank\Domain\Model\BankBranch\BankBranchRepositoryInterface;
use SimpleBank\Domain\Transactions;

class CreateBankBranch
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

            if(!$bankBranchDto) {
                throw new \Exception('Missing bank branch informations.');
            }

            $bankBranch = new BankBranch(
                $this->bankBranchRepository->nextIdentity(),
                $bankBranchDto->name(),
                $bankBranchDto->location()
            );

            if(!$bankBranch) {
                throw new \Exception('Bank branch cannot be null.');
            }

            $this->bankBranchRepository->save($bankBranch);
            $this->transactionalManager->commit();
            
            return true;

        }catch (\Exception $exception) {
            $this->transactionalManager->rollBack();
            return false;
        }
    }
}
