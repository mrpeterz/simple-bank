<?php

namespace SimpleBank\Domain\Model\User;

use Ramsey\Uuid\Uuid;

class UserId
{
    private string $id;

    public function __construct(string $id = null)
    {
        $this->id = null === $id
            ? Uuid::uuid4()->toString() :
            $id;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function equals(UserId $userId): bool
    {
        return $this->id() === $userId->id();
    }

    public function __toString(): string
    {
        return $this->id();
    }
}
