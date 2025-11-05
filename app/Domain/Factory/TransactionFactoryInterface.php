<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\Transaction;
use App\Domain\ValueObject\Money;

interface TransactionFactoryInterface
{
    public static function create(
        int $userId,
        Money $amount,
        ?string $comment = null
    ): Transaction;
}
