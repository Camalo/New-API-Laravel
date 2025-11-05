<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Money;

class Transaction
{
    public const TYPES = [
        'deposit',
        'withdraw',
        'transfer_in',
        'transfer_out'
    ];

    public function __construct(
        public int $userId,
        public string $type,
        public Money $amount,
        public ?string $comment = null,
        public ?\DateTimeImmutable $createdAt = null,
    ) {
        if (!in_array($type, self::TYPES, true)) {
            throw new \InvalidArgumentException("Invalid transaction type: $type");
        }

        if ($this->createdAt === null) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
