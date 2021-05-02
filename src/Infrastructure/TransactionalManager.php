<?php

namespace SimpleBank\Infrastructure;

use Doctrine\DBAL\Connection;
use SimpleBank\Domain\Transactions;

class TransactionalManager implements Transactions
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->connection->commit();
    }

    public function rollBack(): bool
    {
        return $this->connection->rollBack();
    }
}
