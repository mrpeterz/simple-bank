<?php

namespace SimpleBank\Domain\Model\User;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserId
{
    private UuidInterface $userId;

    public function __construct(string $userId)
    {
        $userId = Uuid::fromString($userId);
        $this->userId = $userId;
    }

    public function toString(): string
    {
        return $this->userId->toString();
    }
}
