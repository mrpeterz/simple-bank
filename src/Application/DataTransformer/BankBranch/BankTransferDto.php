<?php

declare(strict_types=1);

namespace SimpleBank\Application\DataTransformer\BankBranch;

use SimpleBank\Application\DataTransformer\Dto;

class BankTransferDto implements Dto
{
    private ?string $fromUserId = null;
    private ?string $toUserId = null;
    private ?float $amount = null;

    public function fromUserId(): ?string
    {
        return $this->fromUserId;
    }

    public function setFromUserId(string $fromUserId): void
    {
        $this->fromUserId = $fromUserId;
    }

    public function toUserId(): ?string
    {
        return $this->toUserId;
    }

    public function setToUserId(string $toUserId): void
    {
        $this->toUserId = $toUserId;
    }

    public function amount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }
}
