<?php

declare(strict_types=1);

namespace SimpleBank\Domain;

interface Transactions
{
    public function beginTransaction(): bool;

    public function commit(): bool;

    public function rollBack(): bool;
}
