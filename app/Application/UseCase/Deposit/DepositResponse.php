<?php

declare(strict_types=1);

namespace App\Application\UseCase\Deposit;

class DepositResponse
{
    public function __construct(
        public readonly int $userId,
        public readonly string $amount,
        public readonly string $comment
    ) {}
}
