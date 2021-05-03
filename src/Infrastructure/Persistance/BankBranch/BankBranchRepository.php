<?php

declare(strict_types = 1);

namespace SimpleBank\Infrastructure\Persistance\BankBranch;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Exception;
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
        try {

            $stm = $this->connection->prepare("INSERT INTO bank_branches VALUES(?,?,?)");
            $stm->bindValue(1, $bankBranch->id());
            $stm->bindValue(2, $bankBranch->name());
            $stm->bindValue(3, $bankBranch->location());

            return $stm->execute();

        } catch (Exception $e) {
            throw  $e;
        }
    }

    public function search(BankBranchId $bankBranchId): ?array
    {
        try {

            $stm = $this->connection->prepare(
                <<<SQL
                SELECT 
                       b.id AS bank_branch_id, 
                       b.name AS bank_branch_name, 
                       b.location AS bank_branch_location 
                FROM bank_branches b 
                WHERE id = ?
SQL
            );

            $stm->bindValue(1, $bankBranchId);
            $rst = $stm->executeQuery();
            return $rst->fetchAssociative();

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function exists(BankBranch $bankBranch): bool
    {
        try {

            $stm = $this->connection->prepare(
                <<<SQL
                SELECT * 
                FROM bank_branches b 
                WHERE b.name = ? 
                AND b.location = ?
SQL
                );

            $stm->bindValue(1, $bankBranch->name());
            $stm->bindValue(2, $bankBranch->location());
            $rst = $stm->executeQuery();
            return $rst->rowCount() > 0;

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function all(): ?array
    {
        try {

            $stm = $this->connection->prepare(
                <<<SQL
            SELECT 
                   b.id AS bank_branch_id, 
                   b.name AS bank_branch_name, 
                   b.location AS bank_branch_location 
            FROM bank_branches b
SQL
            );

            $rst = $stm->executeQuery();
            return $rst->fetchAllAssociative();

        }catch (Exception $e) {
            throw $e;
        }
    }

    public function nextIdentity(): BankBranchId
    {
        return new BankBranchId();
    }


}
