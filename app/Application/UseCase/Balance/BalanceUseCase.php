<?php

declare(strict_types=1);

namespace App\Application\UseCase\Balance;

use App\Domain\Repository\UserBalanceRepositoryInterface;
use Exception;

class BalanceUseCase
{
    public function __construct(
        private UserBalanceRepositoryInterface $balanceRepository
    ) {}

    public function __invoke($userId): BalanceResponse
    {
        $userBalance = $this->balanceRepository->find($userId);

        if (!$userBalance) {
            throw new Exception('Баланс пользователя не найден');
        }

        return new BalanceResponse(
            $userBalance->getUserId(),
            $userBalance->getBalance()->asString()
        );
    }
}
