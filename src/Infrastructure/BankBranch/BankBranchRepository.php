<?php

namespace SimpleBank\Infrastructure\BankBranch;

use Doctrine\DBAL\Driver\Connection;
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
        $stm =
            $this
                ->connection
                ->prepare("INSERT INTO bank_branches VALUES(?,?,?)");

        $stm->bindValue(1, $bankBranch->id());
        $stm->bindValue(2, $bankBranch->name());
        $stm->bindValue(3, $bankBranch->location());

        return $stm->execute();
    }

    public function search(BankBranchId $bankBranchId): ?BankBranch
    {
        $rst =
            $this
                ->connection
                ->fetchAssociative("SELECT * FROM bank_branches WHERE id = ?", [$bankBranchId->id()]);

        if (!$rst) {
            return null;
        }

        return
            new BankBranch(
                new BankBranchId($rst['id']),
                $rst['name'],
                $rst['location']
            );
    }

    public function all(): ?array
    {
        $rst =  $this->connection->fetchAllAssociative("SELECT * FROM bank_branches");

        if (!$rst) {
            return null;
        }

        return $rst;
    }
}
