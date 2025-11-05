<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Transaction;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Infrastructure\Models\TransactionModel;

class EloquentTransactionRepository implements TransactionRepositoryInterface
{
    public function save(Transaction $transaction): void
    {
        TransactionModel::create([
            'user_id' => $transaction->getUserId(),
            'type' => $transaction->getType(),
            'amount' => $transaction->getAmount()->getValue(),
            'comment' => $transaction->getComment()
        ]);
    }
}
