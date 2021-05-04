<?php

declare(strict_types=1);

namespace SimpleBank\Infrastructure\Persistence\BankBranch;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Exception;
use SimpleBank\Domain\Model\BankBranch\StatsRepositoryInterface;

class StatsRepository implements StatsRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function searchByHighestBalance(): ?array
    {
        try {

            $stm = $this->connection->prepare(
                <<<SQL
            SELECT 
               bb.name AS bank_branch_name,
               bb.location AS bank_branch_location,
               ifnull(MAX(ub.balance),0) AS bank_branch_highest
            FROM bank_branches bb
            LEFT JOIN user_balances ub ON bb.id = ub.bank_branch_id
            LEFT JOIN users u ON bb.id = u.bank_branch_id AND ub.user_id = u.id
            GROUP BY bb.id;
SQL
            );

            $rst = $stm->executeQuery();
            return $rst->fetchAllAssociative();

        } catch (Exception $e) {
            throw $e;
        }
    }

    public function searchByTopBankBranches(): ?array
    {
        try {

            $stm = $this->connection->prepare(
                <<<SQL
        SELECT 
               bb.name AS bank_branch_name, 
               bb.location AS bank_branch_location
        FROM bank_branches bb
        JOIN user_balances ub ON bb.id = ub.bank_branch_id
        JOIN users u ON bb.id = u.bank_branch_id AND ub.user_id = u.id
        WHERE ub.balance > 50000
        GROUP BY bb.name, bb.location
        HAVING COUNT(*) > 2;
SQL
            );

            $rst = $stm->executeQuery();
            return $rst->fetchAllAssociative();

        } catch (Exception $e) {
            throw $e;
        }
    }
}
