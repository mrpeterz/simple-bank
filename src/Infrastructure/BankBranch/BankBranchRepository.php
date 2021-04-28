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
        $stm->bindValue(1, $bankBranch->getId());
        $stm->bindValue(2, $bankBranch->getName());
        $stm->bindValue(3, $bankBranch->getLocation());
        return $stm->execute();
    }

    public function search(BankBranchId $bankBranchId): ?BankBranch
    {
        // TODO: Implement search() method.
    }
}
