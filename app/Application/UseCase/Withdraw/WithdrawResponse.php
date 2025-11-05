<?php

declare(strict_types=1);

namespace App\Application\UseCase\Withdraw;

class WithdrawResponse
{
    public function __construct(
        public readonly int $userId,
        public readonly string $amount,
        public readonly string $comment
    ) {}
}
