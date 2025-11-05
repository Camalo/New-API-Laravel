<?php

declare(strict_types=1);

namespace App\Application\UseCase\Transfer;

class TransferRequest{
    public function __construct(
        public readonly int $fromUserId,
        public readonly int $toUserId,
        public readonly float $amount,
        public readonly string $comment
    ) {}
}