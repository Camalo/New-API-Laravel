<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\UserBalance;
use App\Domain\ValueObject\Money;
use App\Domain\Repository\UserBalanceRepositoryInterface;
use App\Infrastructure\Models\UserBalanceModel;

class EloquentUserBalanceRepository implements UserBalanceRepositoryInterface
{
    public function find(int $userId): ?UserBalance
    {
        $model = UserBalanceModel::where('user_id', $userId)->first();


        if($model === null){
            return $model;
        }
        // return new Money(
        //     $model ? (int) ($model->balance * 100) : 0
        // );
        // echo "<pre>";
        // var_dump($model);
        // echo "</pre>";

        return new UserBalance(
            $userId,
            new Money(
                (int) $model->balance
            )
        );
    }

    public function save(UserBalance $userBalance): void
    {
        UserBalanceModel::updateOrCreate(
            ['user_id' => $userBalance->getUserId()],
            ['balance' => $userBalance->getBalance()->getValue()]
        );
    }
}
