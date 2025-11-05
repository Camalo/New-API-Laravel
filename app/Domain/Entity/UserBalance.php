<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Money;
use InvalidArgumentException;

class UserBalance
{
    public function __construct(
        public int $userId,
        public Money $balance
    ) {}

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getBalance(): Money
    {
        return $this->balance;
    }

    public function deposit(Money $amount): void
    {
        $this->balance = $this->balance->add($amount);
    }

    public function withdraw(Money $amount): void
    {
        if ($this->balance->lessThan($amount)) {
            throw new InvalidArgumentException('Баланс не может уходить в минус');
        }

        $this->balance = $this->balance->subtract($amount);
    }
}
