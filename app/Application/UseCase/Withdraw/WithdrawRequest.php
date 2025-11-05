<?php

declare(strict_types=1);

namespace App\Application\UseCase\Withdraw;

class WithdrawRequest
{
    public function __construct(
        public readonly int $userId,
        public readonly float $amount,
        public readonly string $comment
    ) {}
}
