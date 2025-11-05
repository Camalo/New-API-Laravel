<?php

declare(strict_types=1);

namespace App\Application\UseCase\Balance;

class BalanceResponse
{
    public function __construct(
        public readonly int $userId,
        public readonly string $balance
    ) {}
}
