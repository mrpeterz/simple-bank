<?php

namespace SimpleBank\Infrastructure\BankBranch;

use Doctrine\DBAL\Connection;
use SimpleBank\Domain\Model\BankBranch\BankBranch;
use SimpleBank\Domain\Model\BankBranch\BankBranchId;
use SimpleBank\Domain\Model\BankBranch\BankBranchRepositoryInterface;

class BankBranchRepository implements BankBranchRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function save(BankBranch $bankBranch): bool
    {
        $stm = $this->connection->prepare("INSERT INTO bank_branches VALUES(?,?,?)");
        $stm->bindValue(1, $bankBranch->id());
        $stm->bindValue(2, $bankBranch->name());
        $stm->bindValue(3, $bankBranch->location());
        return $stm->execute();
    }

    public function search(BankBranchId $bankBranchId): ?array
    {
        $stm = $this->connection->prepare("SELECT * FROM bank_branches WHERE id = ?");
        $stm->bindValue(1, $bankBranchId);
        return $stm->executeQuery()->fetchAssociative();
    }

    public function all(): ?array
    {
        $stm = $this->connection->prepare("SELECT * FROM bank_branches");
        $rst = $stm->executeQuery();
        return $rst->fetchAllAssociative();
    }

    public function nextIdentity(): BankBranchId
    {
        return new BankBranchId();
    }
}
