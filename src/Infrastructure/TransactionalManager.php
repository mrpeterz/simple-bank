<?php

namespace SimpleBank\Infrastructure;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
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
        try {
            return $this->connection->commit();
        } catch (ConnectionException $e) {
            throw $e;
        }
    }

    public function rollBack(): bool
    {
        try {
            return $this->connection->rollBack();
        } catch (ConnectionException $e) {
            throw $e;
        }
    }
}
