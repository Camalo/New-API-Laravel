<?php

declare(strict_types=1);

namespace App\Application\Factory;

use App\Domain\Entity\Transaction;
use App\Domain\Factory\TransactionFactoryInterface;
use App\Domain\ValueObject\Money;

class WithdrawTransactionFactory implements TransactionFactoryInterface
{
    public static function create(int $userId, Money $amount, ?string $comment = null): Transaction
    {
        return new Transaction(
            userId: $userId,
            type: 'withdraw',
            amount: $amount,
            comment: $comment,
            createdAt: new \DateTimeImmutable()
        );
    }
}
