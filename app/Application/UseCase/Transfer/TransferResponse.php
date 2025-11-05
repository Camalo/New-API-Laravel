<?php

declare(strict_types=1);

namespace App\Application\UseCase\Transfer;

class TransferResponse
{
    public function __construct(
        public readonly int $fromUserId,
        public readonly int $toUserId,
        public readonly string $amount,
        public readonly string $comment
    ) {}
}
